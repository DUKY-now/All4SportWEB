<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Entity\Composer;
use App\Service\PanierService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/panier')]
#[IsGranted('ROLE_USER')]
final class PanierController extends AbstractController
{
    #[Route('', name: 'app_panier_index', methods: ['GET'])]
    public function index(PanierService $panierService): Response
    {
        /** @var \App\Entity\Client $client */
        $client = $this->getUser();

        return $this->render('panier/index.html.twig', [
            'items' => $panierService->getPanierComplet($client),
            'total' => $panierService->getTotal($client),
            'count' => $panierService->getNombreArticles($client),
        ]);
    }

    /** Ajoute N exemplaires d'un produit (quantite passée en POST) */
    #[Route('/ajouter/{id}', name: 'app_panier_add', methods: ['POST'])]
    public function add(int $id, PanierService $panierService, Request $request): Response
    {
        /** @var \App\Entity\Client $client */
        $client   = $this->getUser();
        $quantite = max(1, (int) $request->request->get('quantite', 1));

        for ($i = 0; $i < $quantite; $i++) {
            $panierService->add($client, $id);
        }

        if ($request->isXmlHttpRequest()) {
            return new JsonResponse([
                'success' => true,
                'count'   => $panierService->getNombreArticles($client),
                'total'   => $panierService->getTotal($client),
                'message' => $quantite > 1
                    ? $quantite . ' articles ajoutés au panier !'
                    : 'Produit ajouté au panier !',
            ]);
        }

        $this->addFlash('success', 'Produit ajouté au panier !');
        $referer = $request->headers->get('referer');
        return $this->redirect($referer ?: $this->generateUrl('app_panier_index'));
    }

    /** Diminue de 1 – réponse JSON pour mise à jour sans rechargement */
    #[Route('/diminuer/{id}', name: 'app_panier_decrease', methods: ['POST'])]
    public function decrease(int $id, PanierService $panierService, Request $request): Response
    {
        /** @var \App\Entity\Client $client */
        $client = $this->getUser();
        $panierService->diminuer($client, $id);

        if ($request->isXmlHttpRequest()) {
            $items     = $panierService->getPanierComplet($client);
            $itemFound = null;
            foreach ($items as $item) {
                if ($item['produit']->getId() === $id) {
                    $itemFound = $item;
                    break;
                }
            }
            return new JsonResponse([
                'success'  => true,
                'count'    => $panierService->getNombreArticles($client),
                'total'    => $panierService->getTotal($client),
                'quantite' => $itemFound ? $itemFound['quantite'] : 0,
                'sousTotal'=> $itemFound ? $itemFound['sousTotal'] : 0,
                'removed'  => $itemFound === null,
            ]);
        }

        return $this->redirectToRoute('app_panier_index');
    }

    /** Augmente de 1 – réponse JSON */
    #[Route('/augmenter/{id}', name: 'app_panier_increase', methods: ['POST'])]
    public function increase(int $id, PanierService $panierService, Request $request): Response
    {
        /** @var \App\Entity\Client $client */
        $client = $this->getUser();
        $panierService->add($client, $id);

        if ($request->isXmlHttpRequest()) {
            $items     = $panierService->getPanierComplet($client);
            $itemFound = null;
            foreach ($items as $item) {
                if ($item['produit']->getId() === $id) {
                    $itemFound = $item;
                    break;
                }
            }
            return new JsonResponse([
                'success'  => true,
                'count'    => $panierService->getNombreArticles($client),
                'total'    => $panierService->getTotal($client),
                'quantite' => $itemFound ? $itemFound['quantite'] : 1,
                'sousTotal'=> $itemFound ? $itemFound['sousTotal'] : 0,
            ]);
        }

        return $this->redirectToRoute('app_panier_index');
    }

    #[Route('/supprimer/{id}', name: 'app_panier_remove', methods: ['POST'])]
    public function remove(int $id, PanierService $panierService, Request $request): Response
    {
        /** @var \App\Entity\Client $client */
        $client = $this->getUser();
        $panierService->supprimer($client, $id);

        if ($request->isXmlHttpRequest()) {
            return new JsonResponse([
                'success' => true,
                'count'   => $panierService->getNombreArticles($client),
                'total'   => $panierService->getTotal($client),
            ]);
        }

        $this->addFlash('info', 'Produit retiré du panier.');
        return $this->redirectToRoute('app_panier_index');
    }

    #[Route('/vider', name: 'app_panier_clear', methods: ['POST'])]
    public function clear(PanierService $panierService): Response
    {
        /** @var \App\Entity\Client $client */
        $client = $this->getUser();
        $panierService->vider($client);
        $this->addFlash('info', 'Panier vidé.');
        return $this->redirectToRoute('app_panier_index');
    }

    #[Route('/valider', name: 'app_panier_checkout', methods: ['GET', 'POST'])]
    public function checkout(
        Request $request,
        PanierService $panierService,
        EntityManagerInterface $em
    ): Response {
        /** @var \App\Entity\Client $client */
        $client = $this->getUser();
        $items  = $panierService->getPanierComplet($client);

        if (empty($items)) {
            $this->addFlash('warning', 'Votre panier est vide.');
            return $this->redirectToRoute('app_panier_index');
        }

        if ($request->isMethod('POST')) {

            // ── Vérification du stock avant toute écriture ──────────────────
            foreach ($items as $item) {
                $stock = $item['produit']->getStocks()->first();
                if (!$stock || $stock->getQuantiteDisponible() < $item['quantite']) {
                    $this->addFlash(
                        'danger',
                        sprintf(
                            '"%s" n\'est plus disponible en quantité suffisante (stock : %d).',
                            $item['produit']->getNomProduit(),
                            $stock ? $stock->getQuantiteDisponible() : 0
                        )
                    );
                    return $this->redirectToRoute('app_panier_index');
                }
            }

            // ── Création de la commande ──────────────────────────────────────
            $commande = new Commande();
            $commande->setNumeroCommande('CMD-' . strtoupper(uniqid()));
            $commande->setStatut('en_attente');
            $commande->setModeLivraison($request->request->get('mode_livraison', 'standard'));
            $commande->setDateCommande(new \DateTime());
            $commande->setClient($client);
            $commande->setAdresse($client->getAdresse());
            $em->persist($commande);

            // ── Lignes de commande + décrément du stock ──────────────────────
            foreach ($items as $item) {
                $composer = new Composer();
                $composer->setCommande($commande);
                $composer->setProduit($item['produit']);
                $composer->setQuantite($item['quantite']);
                $composer->setPrixUnitaire((string) $item['produit']->getPrixVente());
                $composer->setSousTotal((string) $item['sousTotal']);
                $em->persist($composer);

                // Décrément du stock
                $stock = $item['produit']->getStocks()->first();
                $stock->setQuantiteDisponible($stock->getQuantiteDisponible() - $item['quantite']);
            }

            $em->flush();

            // ── Vidage du panier BDD ─────────────────────────────────────────
            $panierService->vider($client);

            $this->addFlash('success', 'Commande ' . $commande->getNumeroCommande() . ' passée avec succès !');
            return $this->redirectToRoute('app_panier_confirmation', ['id' => $commande->getId()]);
        }

        return $this->render('panier/checkout.html.twig', [
            'items' => $items,
            'total' => $panierService->getTotal($client),
        ]);
    }

    #[Route('/confirmation/{id}', name: 'app_panier_confirmation', methods: ['GET'])]
    public function confirmation(Commande $commande): Response
    {
        if ($commande->getClient() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }

        return $this->render('panier/confirmation.html.twig', [
            'commande' => $commande,
        ]);
    }
}

