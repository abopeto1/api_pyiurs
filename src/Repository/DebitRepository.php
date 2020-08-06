<?php

namespace App\Repository;

use App\Entity\Debit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Debit|null find($id, $lockMode = null, $lockVersion = null)
 * @method Debit|null findOneBy(array $criteria, array $orderBy = null)
 * @method Debit[]    findAll()
 * @method Debit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DebitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Debit::class);
    }

    /**
     * @return Debit[] Returns an array of Credit objects
     */
    public function findByMonth($value)
    {
        return $this->createQueryBuilder('d')
            ->where("DATE_FORMAT(d.created,'%Y%m') = DATE_FORMAT(:val,'%Y%m')")
            ->setParameter('val', $value)
            ->orderBy('d.id', 'DESC')
            ->getQuery()
            ->getResult();
    }

    // /**
    //  * @return Debit[] Returns an array of Debit objects
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
    public function findOneBySomeField($value): ?Debit
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
