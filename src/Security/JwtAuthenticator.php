<?php

namespace App\Security;

use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Token\Plain;
use Lcobucci\JWT\Validation\Constraint\IssuedBy;
use Lcobucci\JWT\Validation\Constraint\SignedWith;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use function Symfony\Component\String\u;

class JwtAuthenticator extends AbstractAuthenticator
{
    private Configuration $configuration;

    public function __construct(Configuration $configuration)
    {
        $this->configuration = $configuration;
    }

    public function supports(Request $request): ?bool
    {
        return $request->headers->has('Authorization');
    }

    public function authenticate(Request $request): Passport
    {
        $authorization = $request->headers->get('Authorization');
        $token = u($authorization)->after('Bearer ')->toString();

        $parser = $this->configuration->parser();
        $validator = $this->configuration->validator();
        /** @var Plain $jwt */
        $jwt = $parser->parse($token);

        $constraints = [
            new SignedWith($this->configuration->signer(), $this->configuration->signingKey()),
            new IssuedBy('https://localhost')
        ];

        if (!$validator->validate($jwt, ...$constraints)) {
            throw new BadCredentialsException('Invalid token');
        }

        return new SelfValidatingPassport(
            new UserBadge($jwt->claims()->get('sub')),
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        // TODO: Implement onAuthenticationFailure() method.
    }
}
