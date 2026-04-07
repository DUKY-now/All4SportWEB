<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Repository\CommandeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/commandes')]
#[IsGranted('ROLE_USER')]
final class CommandeController extends AbstractController
{
    #[Route('', name: 'app_commande_index', methods: ['GET'])]
    public function index(CommandeRepository $commandeRepository, Request $request): Response|JsonResponse
    {
        /** @var \App\Entity\Client $client */
        $client = $this->getUser();

        $commandes = $commandeRepository->findBy(
            ['client' => $client],
            ['date_commande' => 'DESC']
        );

        // Si c'est une requête API (avec Accept: application/json), retourner JSON
        $acceptHeader = $request->headers->get('Accept', '');
        if (str_contains($acceptHeader, 'application/json')) {
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

        return $this->render('commande/index.html.twig', [
            'commandes' => $commandes,
        ]);
    }

    #[Route('/{id}', name: 'app_commande_show', methods: ['GET'])]
    public function show(Commande $commande, Request $request): Response|JsonResponse
    {
        // Sécurité : vérifier que la commande appartient au client connecté
        if ($commande->getClient() !== $this->getUser()) {
            throw $this->createAccessDeniedException('Vous ne pouvez pas accéder à cette commande.');
        }

        // Calcul du total
        $total = 0;
        foreach ($commande->getComposers() as $composer) {
            $total += (float) $composer->getSousTotal();
        }

        // Si c'est une requête API, retourner JSON
        $acceptHeader = $request->headers->get('Accept', '');
        if (str_contains($acceptHeader, 'application/json')) {
            $composers = [];
            foreach ($commande->getComposers() as $composer) {
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

        return $this->render('commande/show.html.twig', [
            'commande' => $commande,
            'total'    => round($total, 2),
        ]);
    }
}
