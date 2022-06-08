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
}
