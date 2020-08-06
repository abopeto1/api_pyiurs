<?php

namespace App\Repository;

use App\Entity\BilanAccount;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method BilanAccount|null find($id, $lockMode = null, $lockVersion = null)
 * @method BilanAccount|null findOneBy(array $criteria, array $orderBy = null)
 * @method BilanAccount[]    findAll()
 * @method BilanAccount[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BilanAccountRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BilanAccount::class);
    }

    // /**
    //  * @return BilanAccount[] Returns an array of BilanAccount objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?BilanAccount
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
