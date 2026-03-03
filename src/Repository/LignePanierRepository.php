<?php

namespace App\Repository;

use App\Entity\LignePanier;
use App\Entity\Client;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<LignePanier>
 */
class LignePanierRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LignePanier::class);
    }

    public function findByClient(Client $client): array
    {
        return $this->findBy(['client' => $client]);
    }

    public function findOneByClientAndProduit(Client $client, int $produitId): ?LignePanier
    {
        return $this->createQueryBuilder('lp')
            ->where('lp.client = :client')
            ->andWhere('lp.produit = :produit')
            ->setParameter('client', $client)
            ->setParameter('produit', $produitId)
            ->getQuery()
            ->getOneOrNullResult();
    }
}

