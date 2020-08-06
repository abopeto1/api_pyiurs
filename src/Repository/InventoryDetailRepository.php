<?php

namespace App\Repository;

use App\Entity\InventoryDetail;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method InventoryDetail|null find($id, $lockMode = null, $lockVersion = null)
 * @method InventoryDetail|null findOneBy(array $criteria, array $orderBy = null)
 * @method InventoryDetail[]    findAll()
 * @method InventoryDetail[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InventoryDetailRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InventoryDetail::class);
    }

    // /**
    //  * @return InventoryDetail[] Returns an array of InventoryDetail objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?InventoryDetail
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
