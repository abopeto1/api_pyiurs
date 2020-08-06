<?php

namespace App\Repository;

use App\Entity\SoldType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method SoldType|null find($id, $lockMode = null, $lockVersion = null)
 * @method SoldType|null findOneBy(array $criteria, array $orderBy = null)
 * @method SoldType[]    findAll()
 * @method SoldType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SoldTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SoldType::class);
    }

    // /**
    //  * @return SoldType[] Returns an array of SoldType objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?SoldType
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
