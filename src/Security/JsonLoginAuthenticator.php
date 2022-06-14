<?php

namespace App\Security;

use App\Entity\User;
use App\Repository\UserRepository;
use Lcobucci\JWT\Configuration;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\AuthenticatorInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;

class JsonLoginAuthenticator extends AbstractAuthenticator
{
    private UserRepository $userRepository;
    private Configuration $configuration;

    public function __construct(UserRepository $userRepository, Configuration $configuration)
    {
        $this->userRepository = $userRepository;
        $this->configuration = $configuration;
    }

    public function supports(Request $request): ?bool
    {
        return $request->getPathInfo() === '/api/login';
    }

    public function authenticate(Request $request): Passport
    {
        $data = json_decode($request->getContent(), true);
        $email = $data['email'] ?? '';
        $password = $data['password'] ?? '';

        return new Passport(
            new UserBadge($email, function(string $email) {
                $user = $this->userRepository->findOneBy(['email' => $email]);

                if (!$user) {
                    throw new UserNotFoundException();
                }

                return $user;
            }),
            new PasswordCredentials($password)
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        $user = $token->getUser();
        if (!$user instanceof User) {
            throw new \RuntimeException('Invalid user');
        }

        $now = new \DateTimeImmutable();

        $jwt = $this->configuration->builder()
            ->issuedAt($now)
            ->expiresAt($now->modify('+ 15 minutes'))
            ->canOnlyBeUsedAfter($now->modify('+ 1 second'))
            ->issuedBy('https://localhost')
            ->permittedFor('https://localhost', 'https://example.com')
            ->relatedTo($user->getEmail())
            ->getToken(
                $this->configuration->signer(),
                $this->configuration->signingKey()
            );

        return new JsonResponse(['token' => $jwt->toString()]);
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        return new JsonResponse(['error' => $exception->getMessageKey()], 401);
    }
}
