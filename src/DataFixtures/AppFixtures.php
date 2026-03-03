<?php

namespace App\DataFixtures;

use App\Entity\Adresse;
use App\Entity\Client;
use App\Entity\Composer;
use App\Entity\Commande;
use App\Entity\Produit;
use App\Entity\Rayon;
use App\Entity\Stock;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        // Création des rayons
        $rayons = [
            ['nom' => 'Football', 'description' => 'Tous les équipements pour le football'],
            ['nom' => 'Basketball', 'description' => 'Ballons, chaussures et accessoires de basketball'],
            ['nom' => 'Tennis', 'description' => 'Raquettes, balles et équipements de tennis'],
            ['nom' => 'Running', 'description' => 'Chaussures et vêtements pour la course à pied'],
            ['nom' => 'Fitness', 'description' => 'Équipements pour la musculation et le fitness'],
            ['nom' => 'Natation', 'description' => 'Maillots, lunettes et accessoires de natation'],
        ];

        $rayonObjects = [];
        foreach ($rayons as $rayonData) {
            $rayon = new Rayon();
            $rayon->setNomRayon($rayonData['nom']);
            $rayon->setDescription($rayonData['description']);
            $manager->persist($rayon);
            $rayonObjects[] = $rayon;
        }

        // Création des produits
        $produits = [
            // Football
            ['nom' => 'Ballon de football professionnel', 'description' => 'Ballon officiel FIFA, cuir synthétique haute qualité', 'prix' => 29.99, 'rayon' => 0, 'image' => 'football-ball.jpg', 'stock' => 50],
            ['nom' => 'Chaussures de football Nike Mercurial', 'description' => 'Chaussures à crampons pour terrain sec', 'prix' => 89.99, 'rayon' => 0, 'image' => 'football-shoes.jpg', 'stock' => 30],
            ['nom' => 'Maillot de football équipe de France', 'description' => 'Maillot officiel FFF, bleu', 'prix' => 59.99, 'rayon' => 0, 'image' => 'football-jersey.jpg', 'stock' => 45],
            ['nom' => 'Protège-tibias Nike', 'description' => 'Protection légère et confortable', 'prix' => 14.99, 'rayon' => 0, 'image' => 'shin-guards.jpg', 'stock' => 60],

            // Basketball
            ['nom' => 'Ballon de basketball Spalding', 'description' => 'Ballon officiel NBA, taille 7', 'prix' => 34.99, 'rayon' => 1, 'image' => 'basketball-ball.jpg', 'stock' => 40],
            ['nom' => 'Chaussures Nike Air Jordan', 'description' => 'Chaussures montantes pour basketball', 'prix' => 129.99, 'rayon' => 1, 'image' => 'basketball-shoes.jpg', 'stock' => 25],
            ['nom' => 'Panier de basketball portable', 'description' => 'Panier incontourn en hauteur, base lestable', 'prix' => 199.99, 'rayon' => 1, 'image' => 'basketball-hoop.jpg', 'stock' => 15],

            // Tennis
            ['nom' => 'Raquette de tennis Wilson Pro Staff', 'description' => 'Raquette professionnelle, 300g', 'prix' => 89.99, 'rayon' => 2, 'image' => 'tennis-racket.jpg', 'stock' => 20],
            ['nom' => 'Balles de tennis Wilson x3', 'description' => 'Tube de 3 balles pressurized', 'prix' => 8.99, 'rayon' => 2, 'image' => 'tennis-balls.jpg', 'stock' => 100],
            ['nom' => 'Sac de tennis Head', 'description' => 'Sac pour 6 raquettes avec compartiments', 'prix' => 49.99, 'rayon' => 2, 'image' => 'tennis-bag.jpg', 'stock' => 18],

            // Running
            ['nom' => 'Chaussures running Nike Pegasus', 'description' => 'Chaussures amortissantes pour course', 'prix' => 79.99, 'rayon' => 3, 'image' => 'running-shoes.jpg', 'stock' => 55],
            ['nom' => 'Montre GPS Garmin Forerunner', 'description' => 'Montre cardio GPS pour running', 'prix' => 159.99, 'rayon' => 3, 'image' => 'gps-watch.jpg', 'stock' => 12],
            ['nom' => 'Short de running Adidas', 'description' => 'Short léger et respirant', 'prix' => 24.99, 'rayon' => 3, 'image' => 'running-shorts.jpg', 'stock' => 70],
            ['nom' => 'Brassière de sport Nike', 'description' => 'Support optimal pour la course', 'prix' => 34.99, 'rayon' => 3, 'image' => 'sports-bra.jpg', 'stock' => 40],

            // Fitness
            ['nom' => 'Tapis de yoga premium', 'description' => 'Tapis antidérapant 6mm épaisseur', 'prix' => 29.99, 'rayon' => 4, 'image' => 'yoga-mat.jpg', 'stock' => 35],
            ['nom' => 'Haltères réglables 20kg', 'description' => 'Paire d\'haltères ajustables', 'prix' => 89.99, 'rayon' => 4, 'image' => 'dumbbells.jpg', 'stock' => 22],
            ['nom' => 'Bande élastique de résistance', 'description' => 'Set de 5 bandes différentes résistances', 'prix' => 19.99, 'rayon' => 4, 'image' => 'resistance-bands.jpg', 'stock' => 50],
            ['nom' => 'Kettlebell 16kg', 'description' => 'Kettlebell en fonte pour entraînement', 'prix' => 39.99, 'rayon' => 4, 'image' => 'kettlebell.jpg', 'stock' => 28],

            // Natation
            ['nom' => 'Maillot de bain Speedo', 'description' => 'Maillot de compétition résistant au chlore', 'prix' => 44.99, 'rayon' => 5, 'image' => 'swimsuit.jpg', 'stock' => 38],
            ['nom' => 'Lunettes de natation Arena', 'description' => 'Lunettes anti-buée avec protection UV', 'prix' => 19.99, 'rayon' => 5, 'image' => 'swim-goggles.jpg', 'stock' => 65],
            ['nom' => 'Bonnet de natation silicone', 'description' => 'Bonnet confortable et étanche', 'prix' => 9.99, 'rayon' => 5, 'image' => 'swim-cap.jpg', 'stock' => 80],
            ['nom' => 'Plaquettes de natation', 'description' => 'Plaquettes pour entraînement technique', 'prix' => 14.99, 'rayon' => 5, 'image' => 'swim-paddles.jpg', 'stock' => 45],
        ];

        $produitObjects = [];
        foreach ($produits as $produitData) {
            $produit = new Produit();
            $produit->setNomProduit($produitData['nom']);
            $produit->setDescription($produitData['description']);
            $produit->setPrixVente($produitData['prix']);
            $produit->setImageUrl($produitData['image']);
            $manager->persist($produit);
            $produitObjects[] = $produit;

            // Créer le stock associé
            $stock = new Stock();
            $stock->setProduit($produit);
            $stock->setRayon($rayonObjects[$produitData['rayon']]);
            $stock->setLieuStockage('Entrepôt principal A' . ($produitData['rayon'] + 1));
            $stock->setQuantiteDisponible($produitData['stock']);
            $manager->persist($stock);
        }

        // Création des adresses
        $adresses = [
            ['rue' => '123 Rue de la Paix', 'code_postal' => '75001', 'ville' => 'Paris', 'pays' => 'France', 'complement' => 'Appartement 4B'],
            ['rue' => '45 Avenue des Champs-Élysées', 'code_postal' => '75008', 'ville' => 'Paris', 'pays' => 'France', 'complement' => null],
            ['rue' => '78 Boulevard Saint-Germain', 'code_postal' => '75006', 'ville' => 'Paris', 'pays' => 'France', 'complement' => ' Rez-de-chaussée'],
            ['rue' => '12 Rue Nationale', 'code_postal' => '59000', 'ville' => 'Lille', 'pays' => 'France', 'complement' => 'Boîte aux lettres 5'],
            ['rue' => '34 Place de la République', 'code_postal' => '33000', 'ville' => 'Bordeaux', 'pays' => 'France', 'complement' => '3ème étage'],
        ];

        $adresseObjects = [];
        foreach ($adresses as $adresseData) {
            $adresse = new Adresse();
            $adresse->setRue($adresseData['rue']);
            $adresse->setCodePostal($adresseData['code_postal']);
            $adresse->setVille($adresseData['ville']);
            $adresse->setPays($adresseData['pays']);
            $adresse->setComplement($adresseData['complement']);
            $manager->persist($adresse);
            $adresseObjects[] = $adresse;
        }

        // Création des clients
        $clients = [
            ['email' => 'admin@all4sport.com', 'nom' => 'Dupont', 'prenom' => 'Jean', 'telephone' => '0612345678', 'role' => 'ROLE_ADMIN'],
            ['email' => 'client1@example.com', 'nom' => 'Martin', 'prenom' => 'Sophie', 'telephone' => '0623456789', 'role' => 'ROLE_USER'],
            ['email' => 'client2@example.com', 'nom' => 'Bernard', 'prenom' => 'Pierre', 'telephone' => '0634567890', 'role' => 'ROLE_USER'],
            ['email' => 'client3@example.com', 'nom' => 'Durand', 'prenom' => 'Marie', 'telephone' => '0645678901', 'role' => 'ROLE_USER'],
            ['email' => 'client4@example.com', 'nom' => 'Leroy', 'prenom' => 'Lucas', 'telephone' => '0656789012', 'role' => 'ROLE_USER'],
        ];

        $clientObjects = [];
        foreach ($clients as $clientData) {
            $client = new Client();
            $client->setEmail($clientData['email']);
            $client->setNom($clientData['nom']);
            $client->setPrenom($clientData['prenom']);
            $client->setTelephone($clientData['telephone']);
            $client->setRole($clientData['role']);
            $client->setDateInscription(new \DateTime());
            
            // Hasher le mot de passe
            $hashedPassword = $this->passwordHasher->hashPassword($client, 'password123');
            $client->setMotDePasse($hashedPassword);
            
            // Associer une adresse
            $adresseIndex = array_rand($adresseObjects);
            $client->setAdresse($adresseObjects[$adresseIndex]);
            
            $manager->persist($client);
            $clientObjects[] = $client;
        }

        // Création des commandes avec leurs items
        $commandes = [
            [
                'client' => 0,
                'adresse' => 0,
                'statut' => 'livree',
                'mode_livraison' => 'domicile',
                'date' => new \DateTime('-10 days'),
                'items' => [
                    ['produit' => 0, 'quantite' => 2, 'prix' => 29.99],
                    ['produit' => 1, 'quantite' => 1, 'prix' => 89.99],
                ]
            ],
            [
                'client' => 1,
                'adresse' => 1,
                'statut' => 'en_cours',
                'mode_livraison' => 'retrait_magasin',
                'date' => new \DateTime('-3 days'),
                'items' => [
                    ['produit' => 4, 'quantite' => 1, 'prix' => 34.99],
                    ['produit' => 5, 'quantite' => 1, 'prix' => 129.99],
                ]
            ],
            [
                'client' => 2,
                'adresse' => 2,
                'statut' => 'en_attente',
                'mode_livraison' => 'domicile',
                'date' => new \DateTime('-1 day'),
                'items' => [
                    ['produit' => 10, 'quantite' => 1, 'prix' => 79.99],
                    ['produit' => 11, 'quantite' => 1, 'prix' => 159.99],
                    ['produit' => 12, 'quantite' => 2, 'prix' => 24.99],
                ]
            ],
            [
                'client' => 3,
                'adresse' => 3,
                'statut' => 'livree',
                'mode_livraison' => 'domicile',
                'date' => new \DateTime('-20 days'),
                'items' => [
                    ['produit' => 14, 'quantite' => 1, 'prix' => 29.99],
                    ['produit' => 15, 'quantite' => 1, 'prix' => 89.99],
                    ['produit' => 16, 'quantite' => 3, 'prix' => 19.99],
                ]
            ],
            [
                'client' => 4,
                'adresse' => 4,
                'statut' => 'annulee',
                'mode_livraison' => 'domicile',
                'date' => new \DateTime('-5 days'),
                'items' => [
                    ['produit' => 18, 'quantite' => 1, 'prix' => 44.99],
                    ['produit' => 19, 'quantite' => 2, 'prix' => 19.99],
                ]
            ],
        ];

        $commandeCounter = 1;
        foreach ($commandes as $commandeData) {
            $commande = new Commande();
            $commande->setNumeroCommande('CMD-' . str_pad((string)$commandeCounter, 6, '0', STR_PAD_LEFT));
            $commande->setStatut($commandeData['statut']);
            $commande->setModeLivraison($commandeData['mode_livraison']);
            $commande->setDateCommande($commandeData['date']);
            $commande->setClient($clientObjects[$commandeData['client']]);
            $commande->setAdresse($adresseObjects[$commandeData['adresse']]);

            // Créer les items de la commande
            foreach ($commandeData['items'] as $item) {
                $composer = new Composer();
                $composer->setProduit($produitObjects[$item['produit']]);
                $composer->setCommande($commande);
                $composer->setQuantite($item['quantite']);
                $composer->setPrixUnitaire($item['prix']);
                $composer->setSousTotal($item['quantite'] * $item['prix']);
                $manager->persist($composer);
            }

            $manager->persist($commande);
            $commandeCounter++;
        }

        $manager->flush();
    }
}

