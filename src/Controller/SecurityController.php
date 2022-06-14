<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Scheb\TwoFactorBundle\Security\TwoFactor\Provider\Totp\TotpAuthenticatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route('/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        return $this->render('security/login.html.twig', [
            'error' => $authenticationUtils->getLastAuthenticationError(),
            'last_username' => $authenticationUtils->getLastUsername(),
        ]);
    }

    #[Route('/logout')]
    public function logout()
    {
        throw new \Exception('logout() should never be reached');
    }

    #[Route('/authentication/2fa/enable', name: 'app_2fa_enable')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function enable2fa(EntityManagerInterface $entityManager, TotpAuthenticatorInterface $totpAuthenticator)
    {
        $user = $this->getUser();
        if (!$user->isTotpAuthenticationEnabled()) {
            $user->setTotpSecret($totpAuthenticator->generateSecret());

            $entityManager->flush();
        }

        return $this->render('security/enable2fa.html.twig');
    }

    #[Route('/authentication/2fa/qr-code', name: 'app_qr_code')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function displayGoogleAuthenticatorQrCode()
    {
        die('TODO!');
    }
}
