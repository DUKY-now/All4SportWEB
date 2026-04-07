<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Client;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Service\JWTService;

#[Route('/api', name: 'api_')]
class ClientLoginController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserPasswordHasherInterface $passwordHasher,
        private JWTService $jwtService
    ) {}

    #[Route('/client/login', name: 'client_login', methods: ['POST'])]
    public function login(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $email = $data['email'] ?? null;
        $password = $data['mot_de_passe'] ?? null;

        if (!$email || !$password) {
            return new JsonResponse(['success' => false, 'message' => 'Email et mot de passe requis.'], 400);
        }

        $client = $this->entityManager->getRepository(Client::class)->findOneBy(['email' => $email]);

        if (!$client || !$this->passwordHasher->isPasswordValid($client, $password)) {
            return new JsonResponse(['success' => false, 'message' => 'Identifiants incorrects.'], 401);
        }

        // Générer un JWT token avec le rôle réel du client
        $roles = [$client->getRole()]; // Utiliser le rôle réel de la BDD
        $token = $this->jwtService->generateToken(
            $client->getId(),
            $client->getEmail(),
            $roles
        );

        return new JsonResponse([
            'success' => true,
            'token' => $token,
            'user' => [
                'id' => $client->getId(),
                'email' => $client->getEmail(),
                'nom' => $client->getNom(),
                'prenom' => $client->getPrenom(),
                'role' => $client->getRole(),
            ]
        ]);
    }
}
