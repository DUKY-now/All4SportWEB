<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Produit;

#[Route('/api', name: 'api_')]
class ProduitController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {}

    #[Route('/produits', name: 'produits_list', methods: ['GET'])]
    public function list(): JsonResponse
    {
        try {
            $produits = $this->entityManager->getRepository(Produit::class)->findAll();

            $data = [];
            foreach ($produits as $produit) {
                $data[] = [
                    'id' => $produit->getId(),
                    'designation' => $produit->getNomProduit(),
                    'prix' => $produit->getPrixVente(),
                    'description' => $produit->getDescription(),
                    'image_url' => $produit->getImageUrl(),
                ];
            }

            return new JsonResponse($data);
        } catch (\Exception $e) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Erreur lors de la récupération des produits: ' . $e->getMessage()
            ], 500);
        }
    }

    #[Route('/produits/{id}', name: 'produit_show', methods: ['GET'])]
    public function show(int $id): JsonResponse
    {
        try {
            $produit = $this->entityManager->getRepository(Produit::class)->find($id);

            if (!$produit) {
                return new JsonResponse([
                    'success' => false,
                    'message' => 'Produit non trouvé'
                ], 404);
            }

            return new JsonResponse([
                'id' => $produit->getId(),
                'designation' => $produit->getNomProduit(),
                'prix' => $produit->getPrixVente(),
                'description' => $produit->getDescription(),
                'image_url' => $produit->getImageUrl(),
            ]);
        } catch (\Exception $e) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ], 500);
        }
    }
}
