<?php

namespace App\Command;

use App\Entity\Categorie;
use App\Entity\Produit;
use App\Entity\Stock;
use App\Entity\Magasin;
use App\Entity\Avis;
use App\Entity\Client;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:load-sports-data',
    description: 'Charge des données de test pour le magasin de sport',
)]
class LoadSportsDataCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        // Création des catégories
        $categories = [
            ['nom' => 'Running', 'libelle' => 'Équipements pour la course à pied'],
            ['nom' => 'Football', 'libelle' => 'Matériel de football'],
            ['nom' => 'Basketball', 'libelle' => 'Équipements de basketball'],
            ['nom' => 'Tennis', 'libelle' => 'Matériel de tennis'],
            ['nom' => 'Fitness', 'libelle' => 'Équipements de fitness et musculation'],
            ['nom' => 'Natation', 'libelle' => 'Équipements de natation'],
            ['nom' => 'Cyclisme', 'libelle' => 'Matériel de vélo'],
            ['nom' => 'Sports d\'hiver', 'libelle' => 'Équipements pour sports d\'hiver'],
        ];

        $categoriesEntities = [];
        foreach ($categories as $cat) {
            $categorie = new Categorie();
            $categorie->setNomCategorie($cat['nom']);
            $categorie->setLibelle($cat['libelle']);
            $this->entityManager->persist($categorie);
            $categoriesEntities[$cat['nom']] = $categorie;
        }

        // Création d'un magasin
        $magasin = new Magasin();
        $magasin->setNomMagasin('All4Sport Paris Centre');
        $magasin->setAdresse('123 Avenue des Champs-Élysées');
        $magasin->setVille('Paris');
        $magasin->setCodePostal('75008');
        $magasin->setTelephone('0142567890');
        $this->entityManager->persist($magasin);

        // Création de clients pour les avis
        $clients = [];
        $nomsClients = [
            ['nom' => 'Dupont', 'prenom' => 'Jean', 'email' => 'jean.dupont@example.com'],
            ['nom' => 'Martin', 'prenom' => 'Sophie', 'email' => 'sophie.martin@example.com'],
            ['nom' => 'Bernard', 'prenom' => 'Pierre', 'email' => 'pierre.bernard@example.com'],
            ['nom' => 'Dubois', 'prenom' => 'Marie', 'email' => 'marie.dubois@example.com'],
            ['nom' => 'Leroy', 'prenom' => 'Luc', 'email' => 'luc.leroy@example.com'],
        ];

        foreach ($nomsClients as $data) {
            $client = new Client();
            $client->setNom($data['nom']);
            $client->setPrenom($data['prenom']);
            $client->setEmail($data['email']);
            $client->setDateInscription(new \DateTime('2024-01-01'));
            $client->setTelephone('0601020304');
            $client->setPassword('$2y$13$dummyHashedPasswordForTestingOnly'); // Mot de passe hashé fictif
            $this->entityManager->persist($client);
            $clients[] = $client;
        }

        $this->entityManager->flush();

        // Produits Running
        $produitsRunning = [
            [
                'nom' => 'Nike Air Zoom Pegasus 40',
                'description' => 'Chaussure de running polyvalente offrant un excellent amorti et une grande réactivité. Idéale pour les entraînements quotidiens et les longues distances. La technologie Air Zoom assure un retour d\'énergie optimal à chaque foulée.',
                'prix' => '139.99',
                'image' => 'https://static.nike.com/a/images/c_limit,w_592,f_auto/t_product_v1/u_126ab356-44d8-4a06-89b4-fcdcc8df0245,c_scale,fl_relative,w_1.0,h_1.0,fl_layer_apply/9c9f37ee-c228-4c79-95ea-24c8e8b96c64/chaussure-de-running-sur-route-air-zoom-pegasus-40-pour-0pNlqD.png',
                'tailles' => ['38', '39', '40', '41', '42', '43', '44', '45'],
            ],
            [
                'nom' => 'Adidas Ultraboost 23',
                'description' => 'Chaussure de running haute performance avec semelle Boost pour un confort maximal. Empeigne Primeknit adaptative pour un ajustement parfait. Parfaite pour les coureurs exigeants recherchant performances et style.',
                'prix' => '189.99',
                'image' => 'https://assets.adidas.com/images/h_840,f_auto,q_auto,fl_lossy,c_fill,g_auto/fb740c72e92a458db57faf340164ec60_9366/Chaussure_Ultraboost_Light_Noir_HQ6342_01_standard.jpg',
                'tailles' => ['38', '39', '40', '41', '42', '43', '44'],
            ],
            [
                'nom' => 'T-shirt Running Respirant',
                'description' => 'T-shirt technique en tissu respirant évacuant la transpiration. Technologie Dri-FIT pour rester au sec. Coupe ajustée et coutures plates pour éviter les irritations lors des longues courses.',
                'prix' => '34.99',
                'image' => 'https://contents.mediadecathlon.com/p2080490/k$2b66bb54f8e04de0f3a8fe1d4cb9b3b0/sq/t-shirt-running-respirant-homme-dry-noir.jpg',
                'tailles' => ['S', 'M', 'L', 'XL', 'XXL'],
            ],
            [
                'nom' => 'Short Running Léger',
                'description' => 'Short de running ultra-léger avec poche zippée sécurisée pour vos clés et smartphone. Tissu séchage rapide et bande élastique ajustable. Idéal pour vos sorties running par temps chaud.',
                'prix' => '29.99',
                'image' => 'https://contents.mediadecathlon.com/p1782141/k$8c8e8f8e8f8e8f8e8f8e8f8e8f8e8f8/sq/short-running-homme-run-dry-noir.jpg',
                'tailles' => ['S', 'M', 'L', 'XL', 'XXL'],
            ],
        ];

        foreach ($produitsRunning as $data) {
            $produit = $this->createProduit($data, $categoriesEntities['Running'], $magasin, $clients);
            $this->entityManager->persist($produit);
        }

        // Produits Football
        $produitsFootball = [
            [
                'nom' => 'Ballon Nike Premier League Strike',
                'description' => 'Ballon de football officiel aux couleurs de la Premier League. Construction haute visibilité pour un meilleur suivi du ballon. Surface texturée pour un contrôle optimal dans toutes les conditions.',
                'prix' => '29.99',
                'image' => 'https://static.nike.com/a/images/c_limit,w_592,f_auto/t_product_v1/53311e9f-17a5-4f8f-b30e-f8af9bba6f08/ballon-de-football-premier-league-strike-lZ91B0.png',
                'tailles' => ['Taille 5'],
            ],
            [
                'nom' => 'Crampons Adidas Predator Elite',
                'description' => 'Chaussures de football à crampons avec technologie Controlframe pour une accroche maximale. Zone de frappe HybridTouch pour un contrôle de balle exceptionnel. Parfaites pour les terrains gras.',
                'prix' => '249.99',
                'image' => 'https://assets.adidas.com/images/h_840,f_auto,q_auto,fl_lossy,c_fill,g_auto/9ac4c6fb50d74f699685aef60118fe37_9366/Chaussure_de_football_a_crampons_Predator_Elite_Terrain_Gras_Rouge_GZ3346_01_standard.jpg',
                'tailles' => ['38', '39', '40', '41', '42', '43', '44', '45'],
            ],
            [
                'nom' => 'Maillot de Football Pro',
                'description' => 'Maillot de football haute performance en tissu respirant. Technologie anti-transpiration et séchage rapide. Coupe ergonomique pour une liberté de mouvement totale sur le terrain.',
                'prix' => '49.99',
                'image' => 'https://contents.mediadecathlon.com/p2166063/k$66e8e8e8e8e8e8e8e8e8e8e8e8e8e8e8/sq/maillot-de-football-adulte-f500-bleu.jpg',
                'tailles' => ['S', 'M', 'L', 'XL', 'XXL'],
            ],
        ];

        foreach ($produitsFootball as $data) {
            $produit = $this->createProduit($data, $categoriesEntities['Football'], $magasin, $clients);
            $this->entityManager->persist($produit);
        }

        // Produits Basketball
        $produitsBasketball = [
            [
                'nom' => 'Nike LeBron XXI',
                'description' => 'Chaussure de basketball signature LeBron James. Amorti Air Zoom double pour une réactivité explosive. Support latéral renforcé pour les changements de direction rapides. Semelle extérieure sculptée pour une traction multidirectionnelle.',
                'prix' => '199.99',
                'image' => 'https://static.nike.com/a/images/c_limit,w_592,f_auto/t_product_v1/8e8bb83a-5e8d-4f1d-b3a0-f8a8c9e8e8e8/chaussure-de-basketball-lebron-xxi-pour-7wHMNM.png',
                'tailles' => ['40', '41', '42', '43', '44', '45', '46'],
            ],
            [
                'nom' => 'Spalding NBA Official Game Ball',
                'description' => 'Ballon de basketball officiel NBA en cuir composite. Excellent grip et durabilité exceptionnelle. Gonflage parfait pour un rebond optimal. Le ballon préféré des professionnels.',
                'prix' => '149.99',
                'image' => 'https://www.spalding.fr/media/catalog/product/cache/1/image/9df78eab33525d08d6e5fb8d27136e95/8/3/83-385z_nba_official_game_ball_p_1.jpg',
                'tailles' => ['Taille 7'],
            ],
            [
                'nom' => 'Short Basketball Performance',
                'description' => 'Short de basketball en tissu léger et respirant. Poches latérales profondes et cordon de serrage élastique. Design moderne avec empiècements mesh pour une ventilation optimale.',
                'prix' => '39.99',
                'image' => 'https://contents.mediadecathlon.com/p2103145/k$8a8a8a8a8a8a8a8a8a8a8a8a8a8a8a8a/sq/short-de-basketball-adulte-sh500-noir.jpg',
                'tailles' => ['S', 'M', 'L', 'XL', 'XXL'],
            ],
        ];

        foreach ($produitsBasketball as $data) {
            $produit = $this->createProduit($data, $categoriesEntities['Basketball'], $magasin, $clients);
            $this->entityManager->persist($produit);
        }

        // Produits Tennis
        $produitsTennis = [
            [
                'nom' => 'Raquette Wilson Pro Staff RF97',
                'description' => 'Raquette de tennis professionnelle signature Roger Federer. Cadre en graphite tissé pour une stabilité maximale. Poids de 340g pour une puissance et un contrôle exceptionnels. Idéale pour les joueurs avancés.',
                'prix' => '229.99',
                'image' => 'https://www.wilson.com/en-us/media/catalog/product/cache/1/image/9df78eab33525d08d6e5fb8d27136e95/w/r/wr043711u_pro_staff_rf97_autograph_v13.jpg',
                'tailles' => ['Grip 2', 'Grip 3', 'Grip 4'],
            ],
            [
                'nom' => 'Balles de Tennis Dunlop ATP',
                'description' => 'Tube de 4 balles de tennis officielles ATP. Noyau haute performance pour une durabilité accrue. Feutre de qualité premium pour un rebond constant. Parfaites pour la compétition et l\'entraînement intensif.',
                'prix' => '12.99',
                'image' => 'https://contents.mediadecathlon.com/p1648632/k$2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a/sq/balles-de-tennis-dunlop-atp-x4.jpg',
                'tailles' => ['Lot de 4'],
            ],
            [
                'nom' => 'Polo Tennis Technique',
                'description' => 'Polo de tennis en tissu technique respirant. Col côtelé et finitions soignées. Traitement anti-UV pour une protection solaire. Coupe athlétique élégante pour un look professionnel sur le court.',
                'prix' => '44.99',
                'image' => 'https://contents.mediadecathlon.com/p1891234/k$3b3b3b3b3b3b3b3b3b3b3b3b3b3b3b3b/sq/polo-de-tennis-homme-dry-100-blanc.jpg',
                'tailles' => ['S', 'M', 'L', 'XL', 'XXL'],
            ],
        ];

        foreach ($produitsTennis as $data) {
            $produit = $this->createProduit($data, $categoriesEntities['Tennis'], $magasin, $clients);
            $this->entityManager->persist($produit);
        }

        // Produits Fitness
        $produitsFitness = [
            [
                'nom' => 'Haltères Ajustables 2-24kg',
                'description' => 'Set d\'haltères ajustables avec système de sélection rapide du poids. Compact et facile à ranger. Revêtement antidérapant pour une prise en main sécurisée. Parfait pour l\'entraînement à domicile.',
                'prix' => '299.99',
                'image' => 'https://contents.mediadecathlon.com/p1567890/k$4c4c4c4c4c4c4c4c4c4c4c4c4c4c4c4c/sq/halteres-ajustables-2-24kg.jpg',
                'tailles' => ['2-24kg'],
            ],
            [
                'nom' => 'Tapis de Yoga Premium',
                'description' => 'Tapis de yoga haute densité 6mm d\'épaisseur. Surface antidérapante texturée pour une adhérence optimale. Matière écologique sans latex. Dimensions 183x61cm avec sac de transport inclus.',
                'prix' => '49.99',
                'image' => 'https://contents.mediadecathlon.com/p1234567/k$5d5d5d5d5d5d5d5d5d5d5d5d5d5d5d5d/sq/tapis-de-yoga-premium-6mm.jpg',
                'tailles' => ['183x61cm'],
            ],
            [
                'nom' => 'Legging de Sport Femme',
                'description' => 'Legging de fitness taille haute avec effet gainant. Tissu technique stretch 4 directions. Poche arrière zippée pour téléphone. Coutures plates pour un confort optimal pendant l\'effort.',
                'prix' => '39.99',
                'image' => 'https://contents.mediadecathlon.com/p1876543/k$6e6e6e6e6e6e6e6e6e6e6e6e6e6e6e6e/sq/legging-fitness-femme-fit-plus-500-noir.jpg',
                'tailles' => ['XS', 'S', 'M', 'L', 'XL'],
            ],
            [
                'nom' => 'Brassière de Sport High Support',
                'description' => 'Brassière de sport à fort maintien pour activités intensives. Bretelles larges réglables et fermeture dorsale. Tissu respirant évacuant l\'humidité. Bonnets amovibles pour un confort personnalisé.',
                'prix' => '34.99',
                'image' => 'https://contents.mediadecathlon.com/p1654321/k$7f7f7f7f7f7f7f7f7f7f7f7f7f7f7f7f/sq/brassiere-de-sport-high-support-noir.jpg',
                'tailles' => ['S', 'M', 'L', 'XL'],
            ],
        ];

        foreach ($produitsFitness as $data) {
            $produit = $this->createProduit($data, $categoriesEntities['Fitness'], $magasin, $clients);
            $this->entityManager->persist($produit);
        }

        // Produits Natation
        $produitsNatation = [
            [
                'nom' => 'Maillot de Bain Arena Carbon Air²',
                'description' => 'Maillot de natation haute compétition homologué FINA. Tissu ultra-léger Carbon Air² pour une compression optimale. Réduction de la traînée jusqu\'à 38%. Le choix des champions olympiques.',
                'prix' => '449.99',
                'image' => 'https://contents.mediadecathlon.com/p1765432/k$8g8g8g8g8g8g8g8g8g8g8g8g8g8g8g8g/sq/maillot-de-bain-competition-arena-carbon.jpg',
                'tailles' => ['XS', 'S', 'M', 'L', 'XL'],
            ],
            [
                'nom' => 'Lunettes de Natation Speedo Fastskin',
                'description' => 'Lunettes de natation hydrodynamiques avec verres antibuée. Joint en silicone souple pour un confort optimal. Vision panoramique à 180 degrés. Sangle réglable facilement avec système IQfit.',
                'prix' => '39.99',
                'image' => 'https://contents.mediadecathlon.com/p1456789/k$9h9h9h9h9h9h9h9h9h9h9h9h9h9h9h9h/sq/lunettes-natation-speedo-fastskin.jpg',
                'tailles' => ['Unique'],
            ],
            [
                'nom' => 'Bonnet de Bain Silicone',
                'description' => 'Bonnet de bain en silicone premium épais et résistant. Protection optimale des cheveux contre le chlore. Design ergonomique qui ne tire pas les cheveux. Multiples couleurs disponibles.',
                'prix' => '9.99',
                'image' => 'https://contents.mediadecathlon.com/p1987654/k$0i0i0i0i0i0i0i0i0i0i0i0i0i0i0i0i/sq/bonnet-natation-silicone.jpg',
                'tailles' => ['Unique'],
            ],
        ];

        foreach ($produitsNatation as $data) {
            $produit = $this->createProduit($data, $categoriesEntities['Natation'], $magasin, $clients);
            $this->entityManager->persist($produit);
        }

        // Produits Cyclisme
        $produitsCyclisme = [
            [
                'nom' => 'Casque Vélo Aero Pro',
                'description' => 'Casque de vélo route haute performance avec design aérodynamique. 18 aérations pour une ventilation optimale. Système de réglage micrométrique pour un ajustement parfait. Certifié CE EN1078.',
                'prix' => '129.99',
                'image' => 'https://contents.mediadecathlon.com/p1234890/k$1j1j1j1j1j1j1j1j1j1j1j1j1j1j1j1j/sq/casque-velo-route-aero.jpg',
                'tailles' => ['S (52-56cm)', 'M (56-60cm)', 'L (60-64cm)'],
            ],
            [
                'nom' => 'Cuissard Cycliste Pro',
                'description' => 'Cuissard de cyclisme avec peau Endurance Evolution. Bretelles en mesh respirant. Insert gel haute densité pour un confort longue distance. Bandes réfléchissantes pour la sécurité.',
                'prix' => '89.99',
                'image' => 'https://contents.mediadecathlon.com/p1567234/k$2k2k2k2k2k2k2k2k2k2k2k2k2k2k2k2k/sq/cuissard-cycliste-homme-pro.jpg',
                'tailles' => ['S', 'M', 'L', 'XL', 'XXL'],
            ],
            [
                'nom' => 'Gants de Vélo Gel Pro',
                'description' => 'Gants de cyclisme avec renforts gel sur les zones de pression. Paumes en microfibre synthétique respirante. Fermeture velcro ajustable. Languettes entre les doigts pour un retrait facile.',
                'prix' => '29.99',
                'image' => 'https://contents.mediadecathlon.com/p1890567/k$3l3l3l3l3l3l3l3l3l3l3l3l3l3l3l3l/sq/gants-velo-gel-pro.jpg',
                'tailles' => ['S', 'M', 'L', 'XL'],
            ],
        ];

        foreach ($produitsCyclisme as $data) {
            $produit = $this->createProduit($data, $categoriesEntities['Cyclisme'], $magasin, $clients);
            $this->entityManager->persist($produit);
        }

        // Produits Sports d'hiver
        $produitsSportsHiver = [
            [
                'nom' => 'Ski Rossignol Experience 88',
                'description' => 'Skis all-mountain polyvalents pour tous types de neige. Construction bois/titane pour puissance et stabilité. Spatule rockerée pour faciliter les virages. Fixations Look SPX 12 incluses.',
                'prix' => '599.99',
                'image' => 'https://contents.mediadecathlon.com/p1678901/k$4m4m4m4m4m4m4m4m4m4m4m4m4m4m4m4m/sq/ski-rossignol-experience-88.jpg',
                'tailles' => ['160cm', '170cm', '180cm'],
            ],
            [
                'nom' => 'Veste de Ski Imperméable',
                'description' => 'Veste de ski technique avec membrane imperméable 20000mm. Isolation synthétique PrimaLoft. Jupe pare-neige amovible et multiples poches. Capuche compatible casque avec réglages.',
                'prix' => '249.99',
                'image' => 'https://contents.mediadecathlon.com/p1789012/k$5n5n5n5n5n5n5n5n5n5n5n5n5n5n5n5n/sq/veste-ski-homme-impermeable.jpg',
                'tailles' => ['S', 'M', 'L', 'XL', 'XXL'],
            ],
            [
                'nom' => 'Masque de Ski Oakley Prizm',
                'description' => 'Masque de ski avec technologie d\'écran Prizm pour une vision optimale. Traitement antibuée et anti-rayures. Ventilation Airflow pour éviter la condensation. Compatible avec tous les casques.',
                'prix' => '179.99',
                'image' => 'https://contents.mediadecathlon.com/p1890123/k$6o6o6o6o6o6o6o6o6o6o6o6o6o6o6o6o/sq/masque-ski-oakley-prizm.jpg',
                'tailles' => ['Unique'],
            ],
        ];

        foreach ($produitsSportsHiver as $data) {
            $produit = $this->createProduit($data, $categoriesEntities['Sports d\'hiver'], $magasin, $clients);
            $this->entityManager->persist($produit);
        }

        $this->entityManager->flush();

        $io->success('Données de test chargées avec succès !');
        $io->note('Catégories créées : ' . count($categories));
        $io->note('Produits créés : 26 produits avec images, descriptions, prix, tailles et avis');

        return Command::SUCCESS;
    }

    private function createProduit(array $data, Categorie $categorie, Magasin $magasin, array $clients): Produit
    {
        $produit = new Produit();
        $produit->setNomProduit($data['nom']);
        $produit->setDescription($data['description']);
        $produit->setPrix($data['prix']);
        $produit->setImage($data['image']);
        $produit->setCategorie($categorie);

        // Créer des stocks pour chaque taille
        foreach ($data['tailles'] as $taille) {
            $stock = new Stock();
            $stock->setProduit($produit);
            $stock->setMagasin($magasin);
            $stock->setQuantite(rand(10, 100));
            $stock->setSeuilAlerte(5);
            $this->entityManager->persist($stock);
        }

        // Créer des avis aléatoires (entre 2 et 5 avis par produit)
        $nbAvis = rand(2, 5);
        $commentaires = [
            [5, 'Excellent produit ! Très satisfait de mon achat, je recommande vivement.'],
            [5, 'Qualité au top, conforme à mes attentes. Livraison rapide.'],
            [4, 'Très bon produit, rapport qualité/prix correct. Quelques petits détails à améliorer.'],
            [4, 'Bonne qualité générale, confortable et esthétique. Content de mon achat.'],
            [5, 'Parfait ! Exactement ce que je cherchais. Design et performance au rendez-vous.'],
            [3, 'Correct sans plus. Le produit fait le job mais rien d\'exceptionnel.'],
            [4, 'Bon achat, je suis satisfait. La qualité est là même si le prix est un peu élevé.'],
            [5, 'Au top ! Produit de qualité professionnelle. Je le recommande les yeux fermés.'],
            [4, 'Très content, bon compromis entre qualité et prix. Livraison soignée.'],
            [5, 'Incroyable ! Dépasse mes espérances. Le meilleur achat de l\'année.'],
        ];

        for ($i = 0; $i < $nbAvis; $i++) {
            $avis = new Avis();
            $avis->setProduit($produit);
            $avis->setClient($clients[array_rand($clients)]);

            $commentaire = $commentaires[array_rand($commentaires)];
            $avis->setNote($commentaire[0]);
            $avis->setCommentaire($commentaire[1]);

            $date = new \DateTime();
            $date->modify('-' . rand(1, 90) . ' days');
            $avis->setDateAvis($date);

            $this->entityManager->persist($avis);
        }

        return $produit;
    }
}

