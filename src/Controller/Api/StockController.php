<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Stock;

#[Route('/api', name: 'api_')]
class StockController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {}

    #[Route('/stocks', name: 'stocks_list', methods: ['GET'])]
    public function list(): JsonResponse
    {
        try {
            $stocks = $this->entityManager->getRepository(Stock::class)->findAll();

            $data = [];
            foreach ($stocks as $stock) {
                $data[] = [
                    'id' => $stock->getId(),
                    'idProduit' => $stock->getProduit()?->getId(),
                    'designation' => $stock->getProduit()?->getNomProduit(),
                    'quantite' => $stock->getQuantiteDisponible(),
                    'entrepôt' => $stock->getLieuStockage(),
                ];
            }

            return new JsonResponse($data);
        } catch (\Exception $e) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Erreur lors de la récupération des stocks: ' . $e->getMessage()
            ], 500);
        }
    }

    #[Route('/stocks/{id}', name: 'stock_show', methods: ['GET'])]
    public function show(int $id): JsonResponse
    {
        try {
            $stock = $this->entityManager->getRepository(Stock::class)->find($id);

            if (!$stock) {
                return new JsonResponse([
                    'success' => false,
                    'message' => 'Stock non trouvé'
                ], 404);
            }

            return new JsonResponse([
                'id' => $stock->getId(),
                'idProduit' => $stock->getProduit()?->getId(),
                'designation' => $stock->getProduit()?->getNomProduit(),
                'quantite' => $stock->getQuantiteDisponible(),
                'entrepôt' => $stock->getLieuStockage(),
            ]);
        } catch (\Exception $e) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ], 500);
        }
    }

    #[Route('/stocks/produit/{idProduit}', name: 'stocks_by_product', methods: ['GET'])]
    public function getStocksByProduct(int $idProduit): JsonResponse
    {
        try {
            $stocks = $this->entityManager->getRepository(Stock::class)->findBy(['produit' => $idProduit]);

            $data = [];
            foreach ($stocks as $stock) {
                $data[] = [
                    'id' => $stock->getId(),
                    'idProduit' => $stock->getProduit()?->getId(),
                    'quantite' => $stock->getQuantiteDisponible(),
                    'entrepôt' => $stock->getLieuStockage(),
                ];
            }

            return new JsonResponse($data);
        } catch (\Exception $e) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ], 500);
        }
    }
}
