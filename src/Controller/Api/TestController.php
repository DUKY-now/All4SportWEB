<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route("/api", name: "api_")]
#[IsGranted('ROLE_CLIENT')]
class TestController extends AbstractController
{
    #[Route("/test", name: "test", methods: ["GET"])]
    public function test(): JsonResponse
    {
        $user = $this->getUser();

        return new JsonResponse([
            'success' => true,
            'message' => 'JWT token valide!',
            'user' => [
                'id' => $user->getId(),
                'email' => $user->getEmail(),
            ]
        ]);
    }
}

