<?php

namespace App\Repository;

use App\Entity\OrderEcheance;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method OrderEcheance|null find($id, $lockMode = null, $lockVersion = null)
 * @method OrderEcheance|null findOneBy(array $criteria, array $orderBy = null)
 * @method OrderEcheance[]    findAll()
 * @method OrderEcheance[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderEcheanceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrderEcheance::class);
    }

    // /**
    //  * @return OrderEcheance[] Returns an array of OrderEcheance objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?OrderEcheance
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
