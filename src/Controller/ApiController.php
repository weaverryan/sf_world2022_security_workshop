<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ApiController extends AbstractController
{
    #[Route('/api/me', name: 'api_me')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function apiMe()
    {
        return $this->json($this->getUser());
    }

    #[Route('/api/login', name: 'api_login', methods: ['POST'])]
    public function apiLogin()
    {
        throw new \Exception('This will never be executed: intercepted by JsonLoginAuthenticator.');
    }
}
