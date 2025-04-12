<?php

namespace App\Repository;

use App\Entity\Activiter;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Activiter>
 */
class ActiviterRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Activiter::class);
    }
    public function findAllByUser(User $user): array
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();
    }

    public function findByUsers(array $users): array
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.user IN (:users)')
            ->setParameter('users', $users)
            ->orderBy('a.startedAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

}
