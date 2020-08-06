<?php

namespace App\Repository;

use App\Entity\Type;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Type|null find($id, $lockMode = null, $lockVersion = null)
 * @method Type|null findOneBy(array $criteria, array $orderBy = null)
 * @method Type[]    findAll()
 * @method Type[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Type::class);
    }

    public function findByNameAndSegment($type, $segment): ?Type
    {
        return $this->createQueryBuilder('t')
            ->where("t.name like :type")
            ->andWhere('t.segment = :segment')
            ->setParameter('type', '%'.$type.'%')
            ->setParameter('segment', $segment)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    // /**
    //  * @return Type[] Returns an array of Type objects
    //  */
    public function findByProductPeriodAndStatut($date,$statut)
    {
        if($statut == "open"){
            return $this->createQueryBuilder('t')
                ->join('t.products', 'p')
                ->addSelect('p')
                ->join('p.stock', 's')
                ->addSelect('s')
                ->leftJoin("p.billDetails","bd","WITH","bd.rs = 0","bd.id")
                ->addSelect("bd")
                ->where("p.moveStatus = 1")
                ->andWhere("DATE_FORMAT(s.created, '%Y%m%d') < DATE_FORMAT(:date, '%Y%m%d')")
                ->andWhere("(s.available = 1 OR (s.available = 0 AND DATE_FORMAT(bd.created,'%Y%m%d') >= DATE_FORMAT(:date, '%Y%m%d')) AND bd.rs = 0)")
                ->setParameter('date', $date)
                ->getQuery()
                ->getResult();
        }
        if ($statut == "added") {
            return $this->createQueryBuilder('t')
                ->join('t.products', 'p')
                ->addSelect('p')
                ->join('p.stock', 's')
                ->addSelect('s')
                ->where("p.moveStatus = 1")
                ->andWhere("DATE_FORMAT(s.created, '%Y%m%d') = DATE_FORMAT(:date, '%Y%m%d')")
                ->setParameter('date', $date)
                ->getQuery()
                ->getResult();
        }
        if ($statut == "selled") {
            return $this->createQueryBuilder('t')
                ->join('t.products', 'p')
                ->addSelect('p')
                ->join("p.billDetails", "bd")
                ->addSelect("bd")
                ->where("p.moveStatus = 1")
                ->andWhere("DATE_FORMAT(bd.created, '%Y%m%d') = DATE_FORMAT(:date, '%Y%m%d')")
                ->setParameter('date', $date)
                ->getQuery()
                ->getResult();
        }
        if ($statut == "closed") {
            return $this->createQueryBuilder('t')
                ->join('t.products', 'p')
                ->addSelect('p')
                ->join('p.stock', 's')
                ->addSelect('s')
                ->leftJoin("p.billDetails", "bd")
                ->addSelect("bd")
                ->where("p.moveStatus = 1")
                ->andWhere("DATE_FORMAT(s.created, '%Y%m%d') <= DATE_FORMAT(:date, '%Y%m%d')")
                ->andWhere("s.available = 1 OR (s.available = 0 AND DATE_FORMAT(bd.created,'%Y%m%d') > DATE_FORMAT(:date, '%Y%m%d'))")
                ->setParameter('date', $date)
                ->getQuery()
                ->getResult();
        }
    }

    // /**
    //  * @return Type[] Returns an array of Type objects
    //  */
    public function findByProductDate($date)
    {
        return $this->createQueryBuilder('t')
            ->join('t.products','p')
            ->addSelect('p')
            ->where("DATE_FORMAT(p.created,'%Y-%m-%d') = :date")
            ->setParameter('date', $date)
            ->getQuery()
            ->getResult()
        ;
    }

    // /**
    //  * @return Type[] Returns an array of Type objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Type
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
