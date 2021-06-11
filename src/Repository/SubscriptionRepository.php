<?php

namespace App\Repository;

use App\Entity\Subscription;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Subscription|null find($id, $lockMode = null, $lockVersion = null)
 * @method Subscription|null findOneBy(array $criteria, array $orderBy = null)
 * @method Subscription[]    findAll()
 * @method Subscription[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SubscriptionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Subscription::class);
    }


    public function getCurrentSubscription(User $user)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.user = :user_id')
            ->setParameter('user_id', $user->getId())
            ->andWhere('s.dateTo > :date_to')
            ->setParameter('date_to', new \DateTime('now'))
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
