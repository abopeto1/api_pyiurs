<?php

namespace App\Repository;

use App\Entity\DebitEchehance;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method DebitEchehance|null find($id, $lockMode = null, $lockVersion = null)
 * @method DebitEchehance|null findOneBy(array $criteria, array $orderBy = null)
 * @method DebitEchehance[]    findAll()
 * @method DebitEchehance[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DebitEchehanceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DebitEchehance::class);
    }

    // /**
    //  * @return DebitEchehance[] Returns an array of DebitEchehance objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?DebitEchehance
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
