<?php

namespace App\Repository;

use App\Entity\ProprieterTypeActiviter;
use App\Entity\TypeActiviter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ProprieterTypeActiviter>
 */
class ProprieterTypeActiviterRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProprieterTypeActiviter::class);
    }

    public function getProprieterTypesForTypeActivity(TypeActiviter $typeActiviter){
        return $this->createQueryBuilder('p')
            ->innerJoin('p.typeActiviters', 't') // Assure-toi que le nom de la relation est correct
            ->where('t = :typeActiviter')
            ->setParameter('typeActiviter', $typeActiviter)
            ->getQuery()
            ->getResult();
    }
    //    /**
    //     * @return ProprieterTypeActiviter[] Returns an array of ProprieterTypeActiviter objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?ProprieterTypeActiviter
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
