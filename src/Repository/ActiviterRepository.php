<?php

namespace App\Repository;

use App\Entity\Activiter;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Common\Collections\Collection;

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



    public function getUserProgressForEvent(Collection $typeActiviterIds, string $unit, \DateTimeImmutable $startDate, \DateTimeImmutable $endDate): array
    {
    return $this->createQueryBuilder('a')
            ->select('u.id AS user_id, u.username as username, SUM(da.valeur) AS total')
            ->join('a.user', 'u')
            ->join('a.activiterExercices', 'ae')
            ->join('ae.type_activiter', 'ta')
            ->join('ae.dataActiviters', 'da')
            ->join('da.ProprieterActiviter', 'prop')
            ->where('ta.id IN (:typeActiviterIds)')
            ->andWhere('prop.unit = :unit')
            ->andWhere('a.startedAt BETWEEN :startDate AND :endDate')
            ->setParameter('typeActiviterIds', $typeActiviterIds)
            ->setParameter('unit', $unit)
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->groupBy('u.id')
            ->getQuery()
            ->getResult();
    }


    

}
