<?php

namespace App\Repository;

use App\Entity\PrizeItem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method PrizeItem|null find($id, $lockMode = null, $lockVersion = null)
 * @method PrizeItem|null findOneBy(array $criteria, array $orderBy = null)
 * @method PrizeItem[]    findAll()
 * @method PrizeItem[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PrizeItemRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PrizeItem::class);
    }

    // /**
    //  * @return PrizeItem[] Returns an array of PrizeItem objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PrizeItem
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
