<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\Friend;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Friend>
 */
class FriendRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Friend::class);
    }

    /**
     * Vérifie s'il existe une relation d'amitié acceptée entre deux utilisateurs.
     *
     * @param User $user1 Le premier utilisateur.
     * @param User $user2 Le second utilisateur.
     * @return bool Retourne true s'ils sont amis (relation acceptée), false sinon.
     */
    public function areFriends(User $user1, User $user2): bool
    {
        // On ne peut pas être ami avec soi-même
        if ($user1 === $user2) {
            return false;
        }

        // Utilisation du QueryBuilder pour construire la requête DQL
        $qb = $this->createQueryBuilder('f'); // 'f' est l'alias pour l'entité Friend

        $qb->select('COUNT(f.id)') // On compte juste s'il existe au moins une relation
           ->where('f.status = :status_accepted') // Le statut doit être 'accepted'
           ->andWhere(
               // Condition OR : soit user1 est requester ET user2 receiver, OU l'inverse
               $qb->expr()->orX(
                   $qb->expr()->andX(
                       'f.requester = :user1',
                       'f.receiver = :user2'
                   ),
                   $qb->expr()->andX(
                       'f.requester = :user2',
                       'f.receiver = :user1'
                   )
               )
           )
           // Passage des paramètres pour éviter les injections SQL et lier les objets User
           ->setParameter('status_accepted', Friend::STATUS_ACCEPTED) // Utilise la constante définie dans l'entité
           ->setParameter('user1', $user1)
           ->setParameter('user2', $user2)
           ->setMaxResults(1); // Optimisation : dès qu'on trouve une ligne, on s'arrête

        // Exécute la requête et récupère le nombre (0 ou 1 grâce à setMaxResults)
        $result = (int) $qb->getQuery()->getSingleScalarResult();

        // Retourne true si le compte est supérieur à 0, false sinon
        return $result > 0;
    }
    
    /**
     * Trouve l'entité Friend représentant la relation entre deux utilisateurs,
     * quel que soit le sens de la requête.
     *
     * @param User $user1 Le premier utilisateur.
     * @param User $user2 Le second utilisateur.
     * @return Friend|null Retourne l'objet Friend s'il existe, null sinon.
     */
    public function findFriendship(User $user1, User $user2): ?Friend
    {
        // On ne peut pas être ami avec soi-même
        if ($user1 === $user2) {
            return null;
        }

        $qb = $this->createQueryBuilder('f'); // Alias 'f' pour Friend

        $qb->select('f') // Sélectionne l'objet Friend complet
           ->where(
                // Condition OR : soit user1 est requester ET user2 receiver, OU l'inverse
                $qb->expr()->orX(
                    $qb->expr()->andX(
                        'f.requester = :user1',
                        'f.receiver = :user2'
                    ),
                    $qb->expr()->andX(
                        'f.requester = :user2',
                        'f.receiver = :user1'
                    )
                )
            )
           ->setParameter('user1', $user1)
           ->setParameter('user2', $user2)
           // Important : On s'attend à une seule relation (ou zéro)
           // Si tu n'as pas de contrainte d'unicité parfaite, cela peut retourner
           // la première relation trouvée. La contrainte UniqueConstraint est recommandée.
           ->setMaxResults(1);

        // Exécute la requête et tente de récupérer une seule entité, ou null si aucune n'est trouvée
        return $qb->getQuery()->getOneOrNullResult();
    }
    

    //    /**
    //     * @return Friend[] Returns an array of Friend objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('f')
    //            ->andWhere('f.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('f.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Friend
    //    {
    //        return $this->createQueryBuilder('f')
    //            ->andWhere('f.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
