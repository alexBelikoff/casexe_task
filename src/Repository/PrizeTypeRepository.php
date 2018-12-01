<?php

namespace App\Repository;

use App\Entity\PrizeType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method PrizeType|null find($id, $lockMode = null, $lockVersion = null)
 * @method PrizeType|null findOneBy(array $criteria, array $orderBy = null)
 * @method PrizeType[]    findAll()
 * @method PrizeType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PrizeTypeRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PrizeType::class);
    }

    // /**
    //  * @return PrizeType[] Returns an array of PrizeType objects
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
    public function findOneBySomeField($value): ?PrizeType
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
