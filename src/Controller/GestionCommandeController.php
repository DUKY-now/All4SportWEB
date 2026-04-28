<?php
namespace App\Controller;

use App\Entity\Commande;
use App\Repository\CommandeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_OPERATEUR')]
class GestionCommandeController extends AbstractController
{
    #[Route('/gestion-commande', name: 'app_gestion_commande')]
    public function index(CommandeRepository $commandeRepository): Response
    {
        $commandes = $commandeRepository->findAll();
        return $this->render('gestion_commande/index.html.twig', [
            'commandes' => $commandes,
        ]);
    }

    #[Route('/gestion-commande/status/{id}', name: 'app_gestion_commande_status', methods: ['POST'])]
    public function changeStatus(Request $request, Commande $commande, EntityManagerInterface $em): Response
    {
        $newStatus = $request->request->get('status');
        if ($newStatus && in_array($newStatus, ['en_attente', 'en_cours', 'terminee', 'annulee'])) {
            $commande->setStatut($newStatus);
            $em->flush();
            $this->addFlash('success', 'Statut mis à jour !');
        }
        return $this->redirectToRoute('app_gestion_commande');
    }

    #[Route('/gestion-commande/details/{id}', name: 'app_gestion_commande_details', methods: ['GET'])]
    public function details(Commande $commande): Response
    {
        return $this->render('gestion_commande/details.html.twig', [
            'commande' => $commande,
        ]);
    }
}
