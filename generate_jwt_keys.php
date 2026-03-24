<?php

// Script pour générer les clés JWT
$keyDir = __DIR__ . '/config/jwt';

// Créer le dossier s'il n'existe pas
if (!is_dir($keyDir)) {
    mkdir($keyDir, 0755, true);
}

// Générer la clé privée
$privateKeyFile = $keyDir . '/private.pem';
$publicKeyFile = $keyDir . '/public.pem';

// Vérifier si les clés existent déjà
if (file_exists($privateKeyFile) && file_exists($publicKeyFile)) {
    echo "Les clés JWT existent déjà.\n";
    exit(0);
}

// Générer une nouvelle paire de clés RSA
$config = array(
    "private_key_bits" => 4096,
    "private_key_type" => OPENSSL_KEYTYPE_RSA,
);

// Créer la ressource de clé privée
$res = openssl_pkey_new($config);

// Extraire la clé privée de la ressource
openssl_pkey_export($res, $privKey);

// Extraire la clé publique de la ressource
$pubKey = openssl_pkey_get_details($res);
$pubKey = $pubKey["key"];

// Écrire les clés dans les fichiers
file_put_contents($privateKeyFile, $privKey);
file_put_contents($publicKeyFile, $pubKey);

// Définir les permissions
chmod($privateKeyFile, 0600);
chmod($publicKeyFile, 0644);

echo "✅ Clés JWT générées avec succès!\n";
echo "Clé privée: " . $privateKeyFile . "\n";
echo "Clé publique: " . $publicKeyFile . "\n";

