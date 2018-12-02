<?php

namespace App\Repository;

use App\Entity\Lottery;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Lottery|null find($id, $lockMode = null, $lockVersion = null)
 * @method Lottery|null findOneBy(array $criteria, array $orderBy = null)
 * @method Lottery[]    findAll()
 * @method Lottery[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LotteryRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Lottery::class);
    }

    public function findActive():?Lottery
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.active = :val')
            ->setParameter('val', TRUE)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }

    // /**
    //  * @return Lottery[] Returns an array of Lottery objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Lottery
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
