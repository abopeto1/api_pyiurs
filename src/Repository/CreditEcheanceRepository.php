<?php

namespace App\Repository;

use App\Entity\CreditEcheance;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method CreditEcheance|null find($id, $lockMode = null, $lockVersion = null)
 * @method CreditEcheance|null findOneBy(array $criteria, array $orderBy = null)
 * @method CreditEcheance[]    findAll()
 * @method CreditEcheance[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CreditEcheanceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CreditEcheance::class);
    }

    // /**
    //  * @return CreditEcheance[] Returns an array of CreditEcheance objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CreditEcheance
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
