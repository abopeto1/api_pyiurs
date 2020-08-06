<?php

namespace App\Repository;

use App\Entity\SoldOutProduct;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method SoldOutProduct|null find($id, $lockMode = null, $lockVersion = null)
 * @method SoldOutProduct|null findOneBy(array $criteria, array $orderBy = null)
 * @method SoldOutProduct[]    findAll()
 * @method SoldOutProduct[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SoldOutProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SoldOutProduct::class);
    }

    // /**
    //  * @return SoldOutProduct[] Returns an array of SoldOutProduct objects
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
    public function findOneBySomeField($value): ?SoldOutProduct
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
