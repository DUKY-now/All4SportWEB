<?php

namespace App\Service;

use App\Entity\Client;
use App\Entity\LignePanier;
use App\Repository\LignePanierRepository;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;

class PanierService
{
    public function __construct(
        private EntityManagerInterface $em,
        private LignePanierRepository $lignePanierRepository,
        private ProduitRepository $produitRepository
    ) {}

    /** Ajoute ou incrémente un produit dans le panier BDD */
    public function add(Client $client, int $produitId): void
    {
        $ligne = $this->lignePanierRepository->findOneByClientAndProduit($client, $produitId);

        if ($ligne) {
            $ligne->setQuantite($ligne->getQuantite() + 1);
        } else {
            $produit = $this->produitRepository->find($produitId);
            if (!$produit) return;

            $ligne = new LignePanier();
            $ligne->setClient($client);
            $ligne->setProduit($produit);
            $ligne->setQuantite(1);
            $this->em->persist($ligne);
        }

        $this->em->flush();
    }

    /** Diminue la quantité d'un produit (supprime la ligne si qty atteint 0) */
    public function diminuer(Client $client, int $produitId): void
    {
        $ligne = $this->lignePanierRepository->findOneByClientAndProduit($client, $produitId);
        if (!$ligne) return;

        if ($ligne->getQuantite() > 1) {
            $ligne->setQuantite($ligne->getQuantite() - 1);
        } else {
            $this->em->remove($ligne);
        }

        $this->em->flush();
    }

    /** Supprime complètement une ligne du panier */
    public function supprimer(Client $client, int $produitId): void
    {
        $ligne = $this->lignePanierRepository->findOneByClientAndProduit($client, $produitId);
        if ($ligne) {
            $this->em->remove($ligne);
            $this->em->flush();
        }
    }

    /** Vide tout le panier du client */
    public function vider(Client $client): void
    {
        foreach ($this->lignePanierRepository->findByClient($client) as $ligne) {
            $this->em->remove($ligne);
        }
        $this->em->flush();
    }

    /** Retourne les lignes du panier avec sous-totaux */
    public function getPanierComplet(Client $client): array
    {
        $lignes = $this->lignePanierRepository->findByClient($client);
        $result = [];

        foreach ($lignes as $ligne) {
            $result[] = [
                'ligne'     => $ligne,
                'produit'   => $ligne->getProduit(),
                'quantite'  => $ligne->getQuantite(),
                'sousTotal' => $ligne->getSousTotal(),
            ];
        }

        return $result;
    }

    /** Total du panier */
    public function getTotal(Client $client): float
    {
        $total = 0;
        foreach ($this->lignePanierRepository->findByClient($client) as $ligne) {
            $total += $ligne->getSousTotal();
        }
        return round($total, 2);
    }

    /** Nombre total d'articles dans le panier */
    public function getNombreArticles(Client $client): int
    {
        $lignes = $this->lignePanierRepository->findByClient($client);
        return array_sum(array_map(fn($l) => $l->getQuantite(), $lignes));
    }
}
