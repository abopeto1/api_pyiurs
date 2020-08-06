<?php

namespace App\Repository;

use App\Entity\BillDetails;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method BillDetails|null find($id, $lockMode = null, $lockVersion = null)
 * @method BillDetails|null findOneBy(array $criteria, array $orderBy = null)
 * @method BillDetails[]    findAll()
 * @method BillDetails[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BillDetailsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BillDetails::class);
    }

    /**
     * @return BillDetails[] Returns an array of BillDetails objects
     */
    public function findByInterval($start,$end)
    {
        return $this->createQueryBuilder('b')
            ->where("DATE_FORMAT(b.created,'%Y%m%d') >= DATE_FORMAT(:start,'%Y%m%d') AND DATE_FORMAT(b.created,'%Y%m%d') <= DATE_FORMAT(:end,'%Y%m%d')")
            ->setParameter('start',$start)->setParameter('end',$end)
            ->getQuery()
            ->getResult()
        ;
    }
    // /**
    //  * @return BillDetails[] Returns an array of BillDetails objects
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
    public function findOneBySomeField($value): ?BillDetails
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
