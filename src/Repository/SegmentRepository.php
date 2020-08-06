<?php

namespace App\Repository;

use App\Entity\Segment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Segment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Segment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Segment[]    findAll()
 * @method Segment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SegmentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Segment::class);
    }

    /**
     * @param string $start The begin date
     * @param string $end the end Date
     * @return Segment[] Returns an array of Segment objects
     */
    public function findProductsSellsReports($start,$end)
    {
      return $this->createQueryBuilder('s')
          ->orderBy('s.id', 'DESC')
          ->leftJoin('s.types','t')
          ->addSelect('t')
          ->leftJoin('t.products','p')
          ->addSelect('p')
          ->join('p.billDetails','bd','WITH',
          "DATE_FORMAT(bd.created,'%Y%m%d') >= DATE_FORMAT(:start,'%Y%m%d') AND
            DATE_FORMAT(bd.created,'%Y%m%d') <= DATE_FORMAT(:end,'%Y%m%d')",'bd.id')
          ->addSelect('bd')
          ->setParameter(':start',$start)->setParameter(':end',$end)
          ->getQuery()
          ->getResult()
      ;
    }

    /**
     * @return Segment[] Returns an array of Segment objects
     */
    public function findProductsSellsYear($year)
    {
      return $this->createQueryBuilder('s')
          ->orderBy('s.id', 'DESC')
          ->leftJoin('s.types','t')
          ->addSelect('t')
          ->leftJoin('t.products','p')
          ->addSelect('p')
          ->join('p.billDetails','bd','WITH',"DATE_FORMAT(bd.created,'%Y') = :year",'bd.id')
          ->addSelect('bd')
          ->setParameter(':year',$year)
          ->getQuery()
          ->getResult()
      ;
    }

    /**
     * @return Segment[] Returns an array of Segment objects
     */
    public function findByStock()
    {
      return $this->createQueryBuilder('s')
          ->orderBy('s.id', 'DESC')
          ->leftJoin('s.types','t')
          ->addSelect('t')
          ->leftJoin('s.products','p')
          ->addSelect('p')
          ->join('p.stock','st')
          ->addSelect('st')
          ->where('p.moveStatus = 1')
          ->getQuery()
          ->getResult()
      ;
    }

    // /**
    //  * @return Segment[] Returns an array of Segment objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Segment
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
