<?php

namespace App\Security;

use App\Service\JWTService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Client;

class JWTAuthenticator extends AbstractAuthenticator
{
    public function __construct(
        private JWTService $jwtService,
        private EntityManagerInterface $entityManager
    ) {}

    public function supports(Request $request): ?bool
    {
        // Rejeter les requêtes sans Authorization header
        return $request->headers->has('Authorization') || $request->getPathInfo() === '/api/client/login' || $request->getPathInfo() === '/api/login' || str_starts_with($request->getPathInfo(), '/login') || str_starts_with($request->getPathInfo(), '/register');
    }

    public function authenticate(Request $request): Passport
    {
        $authHeader = $request->headers->get('Authorization');

        if (!$authHeader || !str_starts_with($authHeader, 'Bearer ')) {
            throw new AuthenticationException('No token provided');
        }

        $token = substr($authHeader, 7);
        $decoded = $this->jwtService->validateToken($token);

        if (!$decoded) {
            throw new AuthenticationException('Invalid token');
        }

        $userId = $decoded['userId'] ?? null;
        $email = $decoded['email'] ?? null;

        if (!$userId || !$email) {
            throw new AuthenticationException('Invalid token format');
        }

        // Charger l'utilisateur depuis la BDD
        $user = $this->entityManager->getRepository(Client::class)->findOneBy(['email' => $email]);

        if (!$user) {
            throw new AuthenticationException('User not found');
        }

        return new SelfValidatingPassport(
            new UserBadge($email, function($email) use ($user) {
                return $user;
            })
        );
    }

    public function onAuthenticationSuccess(Request $request, \Symfony\Component\Security\Core\Authentication\Token\TokenInterface $token, string $firewallName): ?\Symfony\Component\HttpFoundation\Response
    {
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?\Symfony\Component\HttpFoundation\Response
    {
        return new JsonResponse(['error' => $exception->getMessageKey()], 401);
    }
}
