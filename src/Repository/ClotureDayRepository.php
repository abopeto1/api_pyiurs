<?php

namespace App\Repository;

use App\Entity\ClotureDay;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method ClotureDay|null find($id, $lockMode = null, $lockVersion = null)
 * @method ClotureDay|null findOneBy(array $criteria, array $orderBy = null)
 * @method ClotureDay[]    findAll()
 * @method ClotureDay[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClotureDayRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ClotureDay::class);
    }

    /**
     * @return ClotureDay[] Returns an array of ClotureDay objects
     */
    public function findByMonth($value)
    {
      if($value == ""){
        return $this->createQueryBuilder('c')
            ->andWhere("DATE_FORMAT(c.created,'%Y%m') = DATE_FORMAT(CURRENT_TIMESTAMP(),'%Y%m')")
            ->orderBy('c.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
      } else {
        return $this->createQueryBuilder('c')
            ->andWhere("DATE_FORMAT(c.created,'%Y%m') = DATE_FORMAT(:date,'%Y%m')")
            ->setParameter('date', $value)
            ->orderBy('c.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
      }
    }

    public function findByDate($value): ?ClotureDay
    {
      return $this->createQueryBuilder('c')
          ->andWhere("DATE_FORMAT(c.created,'%Y-%m-%d') = :val")
          ->setParameter('val', $value)
          ->getQuery()
          ->getOneOrNullResult()
      ;
    }

    /**
     * @return ClotureDay[] Returns an array of ClotureDay objects
     */
    public function findWithBeganDate($value)
    {
        return $this->createQueryBuilder('c')
            ->where("DATE_FORMAT(c.created,'%Y%m%d') >= DATE_FORMAT(:val,'%Y%m%d')")
            ->setParameter('val', $value)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param string $start A string start date with format "Y-m-d"
     * @param string $end A string end date with format "Y-m-d"
     * @return ClotureDay[] Returns an array of ClotureDay objects
     */
    public function findWithInterval($start,$end)
    {
        return $this->createQueryBuilder('c')
            ->where("DATE_FORMAT(c.created,'%Y-%m-%d') >= :start AND DATE_FORMAT(c.created,'%Y-%m-%d') <= :end")
            ->orderBy('c.id', 'DESC')
            ->setParameter('start',$start)->setParameter('end',$end)
            ->getQuery()
            ->getResult()
        ;
    }

    // /**
    //  * @return ClotureDay[] Returns an array of ClotureDay objects
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
    public function findOneBySomeField($value): ?ClotureDay
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
