<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Repository\CommandeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/commandes')]
#[IsGranted('ROLE_USER')]
final class CommandeController extends AbstractController
{
    #[Route('', name: 'app_commande_index', methods: ['GET'])]
    public function index(CommandeRepository $commandeRepository): Response
    {
        /** @var \App\Entity\Client $client */
        $client = $this->getUser();

        $commandes = $commandeRepository->findBy(
            ['client' => $client],
            ['date_commande' => 'DESC']
        );

        return $this->render('commande/index.html.twig', [
            'commandes' => $commandes,
        ]);
    }

    #[Route('/{id}', name: 'app_commande_show', methods: ['GET'])]
    public function show(Commande $commande): Response
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

        return $this->render('commande/show.html.twig', [
            'commande' => $commande,
            'total'    => round($total, 2),
        ]);
    }
}

