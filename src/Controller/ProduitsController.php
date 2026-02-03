<?php

namespace App\Controller;

use App\Repository\ProduitRepository;
use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ProduitsController extends AbstractController
{
    #[Route('/produits', name: 'app_produits')]
    public function index(ProduitRepository $produitRepository, CategorieRepository $categorieRepository): Response
    {
        $produits = $produitRepository->findAll();
        $categories = $categorieRepository->findAll();

        // Calcul de la moyenne des notes pour chaque produit
        $produitsAvecMoyenne = [];
        foreach ($produits as $produit) {
            $avis = $produit->getAvis();
            $sommeNotes = 0;
            $nbAvis = count($avis);

            if ($nbAvis > 0) {
                foreach ($avis as $avi) {
                    $sommeNotes += $avi->getNote();
                }
                $moyenne = $sommeNotes / $nbAvis;
            } else {
                $moyenne = 0;
            }

            $produitsAvecMoyenne[] = [
                'produit' => $produit,
                'moyenneAvis' => $moyenne,
                'nbAvis' => $nbAvis
            ];
        }

        return $this->render('produits/index.html.twig', [
            'produits' => $produitsAvecMoyenne,
            'categories' => $categories,
        ]);
    }
}
