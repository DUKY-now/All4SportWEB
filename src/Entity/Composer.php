<?php

namespace App\Entity;

use App\Repository\ComposerRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ComposerRepository::class)]
class Composer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $quantite = null;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    private ?string $prix_unitaire = null;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    private ?string $sous_total = null;

    #[ORM\ManyToOne(inversedBy: 'composers')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Commande $commande = null;

    #[ORM\ManyToOne(inversedBy: 'composers')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Produit $produit = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): static
    {
        $this->quantite = $quantite;
        $this->updateSousTotal();

        return $this;
    }

    public function getPrixUnitaire(): ?string
    {
        return $this->prix_unitaire;
    }

    public function setPrixUnitaire(string $prix_unitaire): static
    {
        $this->prix_unitaire = $prix_unitaire;
        $this->updateSousTotal();

        return $this;
    }

    public function getSousTotal(): ?string
    {
        return $this->sous_total;
    }

    public function setSousTotal(string $sous_total): static
    {
        $this->sous_total = $sous_total;

        return $this;
    }

    private function updateSousTotal(): void
    {
        if ($this->quantite !== null && $this->prix_unitaire !== null) {
            $this->sous_total = (string)($this->quantite * (float)$this->prix_unitaire);
        }
    }

    public function getCommande(): ?Commande
    {
        return $this->commande;
    }

    public function setCommande(?Commande $commande): static
    {
        $this->commande = $commande;

        return $this;
    }

    public function getProduit(): ?Produit
    {
        return $this->produit;
    }

    public function setProduit(?Produit $produit): static
    {
        $this->produit = $produit;

        return $this;
    }
}

