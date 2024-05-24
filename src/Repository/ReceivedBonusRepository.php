<?php

namespace App\Repository;

use App\Entity\ReceivedBonus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ReceivedBonusRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ReceivedBonus::class);
    }

    public function findByClientId(int $clientId, int $page, int $limit)
    {
        return $this->createQueryBuilder('r')
            ->where('r.clientId = :clientId')
            ->setParameter('clientId', $clientId)
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }
}
