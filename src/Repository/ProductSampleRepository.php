<?php

namespace App\Repository;

use App\Entity\ProductSample;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method ProductSample|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProductSample|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProductSample[]    findAll()
 * @method ProductSample[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductSampleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductSample::class);
    }

    // /**
    //  * @return ProductSample[] Returns an array of ProductSample objects
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
    public function findOneBySomeField($value): ?ProductSample
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
