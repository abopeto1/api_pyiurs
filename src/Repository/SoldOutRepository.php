<?php

namespace App\Repository;

use App\Entity\SoldOut;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method SoldOut|null find($id, $lockMode = null, $lockVersion = null)
 * @method SoldOut|null findOneBy(array $criteria, array $orderBy = null)
 * @method SoldOut[]    findAll()
 * @method SoldOut[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SoldOutRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SoldOut::class);
    }

    // /**
    //  * @return SoldOut[] Returns an array of SoldOut objects
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
    public function findOneBySomeField($value): ?SoldOut
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
