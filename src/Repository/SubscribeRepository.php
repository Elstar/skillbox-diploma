<?php

namespace App\Repository;

use App\Entity\Subscribe;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Subscribe|null find($id, $lockMode = null, $lockVersion = null)
 * @method Subscribe|null findOneBy(array $criteria, array $orderBy = null)
 * @method Subscribe[]    findAll()
 * @method Subscribe[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SubscribeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Subscribe::class);
    }


    public function getCurrentSubscribe(User $user)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.user = ' . $user->getId())
            ->andWhere('s.dateTo > :date_to')
            ->setParameter('date_to', new \DateTime('now'))
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
