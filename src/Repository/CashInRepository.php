<?php

namespace App\Repository;

use App\Entity\CashIn;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method CashIn|null find($id, $lockMode = null, $lockVersion = null)
 * @method CashIn|null findOneBy(array $criteria, array $orderBy = null)
 * @method CashIn[]    findAll()
 * @method CashIn[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CashInRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CashIn::class);
    }

    /**
     * @return CashIn[] Returns an array of CashIn objects
     */
    public function findWithDay($day)
    {
        return $this->createQueryBuilder('c')
            ->where("DATE_FORMAT(c.created,'%Y-%m-%d') = DATE_FORMAT(:day,'%Y-%m-%d')")
            ->setParameter('day', $day)
            ->orderBy('c.id', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return CashIn[] Returns an array of CashIn objects
     */
    public function findByMonth($value)
    {
        return $this->createQueryBuilder('c')
            ->where("DATE_FORMAT(c.created,'%Y%m') = DATE_FORMAT(:val,'%Y%m')")
            ->setParameter('val', $value)
            ->orderBy('c.id', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return BillDetails[] Returns an array of CashIn objects
     */
    public function findByInterval($start,$end)
    {
        return $this->createQueryBuilder('c')
            ->where("DATE_FORMAT(c.created,'%Y-%m-%d') >= :start AND DATE_FORMAT(c.created,'%Y-%m-%d') <= :end")
            ->orderBy('c.id', 'DESC')
            ->setParameter('start',$start)->setParameter('end',$end)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return CashIn[] Returns an array of CashIn objects
     */
    public function findByDate($value)
    {
        return $this->createQueryBuilder('c')
            ->where("DATE_FORMAT(c.created,'%Y%m%d') = DATE_FORMAT(:val,'%Y%m%d')")
            ->setParameter('val', $value)
            ->orderBy('c.id', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    // /**
    //  * @return CashIn[] Returns an array of CashIn objects
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
    public function findOneBySomeField($value): ?CashIn
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
