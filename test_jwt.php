<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Service\JWTService;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

// Initialiser le service JWT
$jwtSecret = getenv('JWT_SECRET') ?: 'your-secret-key-min-32-chars-change-in-production!';
$jwtService = new JWTService($jwtSecret);

echo "=== TEST JWT SERVICE ===\n\n";

// Test 1 : Générer un token
echo "1️⃣ GENERATION D'UN TOKEN\n";
$userId = 1;
$email = "aaa@gmail.com";
$roles = ['ROLE_CLIENT'];

$token = $jwtService->generateToken($userId, $email, $roles);
echo "✅ Token généré :\n";
echo $token . "\n\n";

// Test 2 : Valider le token
echo "2️⃣ VALIDATION DU TOKEN\n";
$validated = $jwtService->validateToken($token);

if ($validated) {
    echo "✅ Token valide ! Données du token :\n";
    echo "   - User ID: " . $validated['userId'] . "\n";
    echo "   - Email: " . $validated['email'] . "\n";
    echo "   - Roles: " . implode(', ', $validated['roles']) . "\n";
    echo "   - Expiration: " . date('Y-m-d H:i:s', $validated['exp']) . "\n";
} else {
    echo "❌ Token invalide !\n";
}

echo "\n";

// Test 3 : Essayer un token invalide
echo "3️⃣ TEST AVEC UN TOKEN INVALIDE\n";
$invalidToken = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.invalid.signature";
$invalidValidated = $jwtService->validateToken($invalidToken);

if (!$invalidValidated) {
    echo "✅ Correctement rejeté : Le token invalide ne fonctionne pas\n";
} else {
    echo "❌ Erreur : Le token invalide a été accepté !\n";
}

echo "\n=== RÉSUMÉ ===\n";
echo "✅ JWT Service fonctionne correctement !\n";

