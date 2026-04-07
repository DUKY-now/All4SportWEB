<?php

namespace App\Controller\Api;

use App\Repository\CommandeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api', name: 'api_')]
#[IsGranted('ROLE_USER')]
class CommandeApiController extends AbstractController
{
    #[Route('/commandes', name: 'commandes', methods: ['GET'])]
    public function getCommandes(CommandeRepository $commandeRepository): JsonResponse
    {
        /** @var \App\Entity\Client $client */
        $client = $this->getUser();

        $commandes = $commandeRepository->findBy(
            ['client' => $client],
            ['date_commande' => 'DESC']
        );

        $data = [];
        foreach ($commandes as $commande) {
            $adresse = $commande->getAdresse();
            $data[] = [
                'id' => $commande->getId(),
                'date' => $commande->getDateCommande()->format('Y-m-d H:i:s'),
                'statut' => $commande->getStatut(),
                'adresse' => $adresse ? $adresse->getRue() . ', ' . $adresse->getCodePostal() . ' ' . $adresse->getVille() : null,
            ];
        }

        return new JsonResponse([
            'success' => true,
            'data' => $data,
            'count' => count($data)
        ]);
    }

    #[Route('/commandes/{id}', name: 'commande_detail', methods: ['GET'])]
    public function getCommande(int $id, CommandeRepository $commandeRepository): JsonResponse
    {
        /** @var \App\Entity\Client $client */
        $client = $this->getUser();

        $commande = $commandeRepository->find($id);

        if (!$commande || $commande->getClient() !== $client) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Commande non trouvée'
            ], 404);
        }

        $total = 0;
        $composers = [];
        foreach ($commande->getComposers() as $composer) {
            $total += (float) $composer->getSousTotal();
            $composers[] = [
                'produit' => $composer->getProduit()?->getNom(),
                'quantite' => $composer->getQuantite(),
                'prix_unitaire' => $composer->getPrixUnitaire(),
                'sous_total' => $composer->getSousTotal(),
            ];
        }

        return new JsonResponse([
            'success' => true,
            'data' => [
                'id' => $commande->getId(),
                'date' => $commande->getDateCommande()->format('Y-m-d H:i:s'),
                'statut' => $commande->getStatut(),
                'adresse' => $commande->getAdresse() ? $commande->getAdresse()->getRue() . ', ' . $commande->getAdresse()->getCodePostal() . ' ' . $commande->getAdresse()->getVille() : null,
                'composers' => $composers,
                'total' => round($total, 2),
            ]
        ]);
    }
}
