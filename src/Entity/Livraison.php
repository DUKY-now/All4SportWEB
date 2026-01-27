<?php

namespace App\Entity;

use App\Repository\LivraisonRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LivraisonRepository::class)]
class Livraison
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $numeroSuivi = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $dateLivraison = null;

    #[ORM\Column(length: 50)]
    private ?string $statut = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?commande $commande = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumeroSuivi(): ?string
    {
        return $this->numeroSuivi;
    }

    public function setNumeroSuivi(string $numeroSuivi): static
    {
        $this->numeroSuivi = $numeroSuivi;

        return $this;
    }

    public function getDateLivraison(): ?\DateTime
    {
        return $this->dateLivraison;
    }

    public function setDateLivraison(?\DateTime $dateLivraison): static
    {
        $this->dateLivraison = $dateLivraison;

        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): static
    {
        $this->statut = $statut;

        return $this;
    }

    public function getCommande(): ?commande
    {
        return $this->commande;
    }

    public function setCommande(?commande $commande): static
    {
        $this->commande = $commande;

        return $this;
    }
}
