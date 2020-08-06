<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use App\Repository\AbstractRepository;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /**
     * @return Product[] Returns an array of Product objects
     */
    public function search($term, $order = 'asc', $limit = 10, $offset = 0)
    {
        $qb = $this
                ->createQueryBuilder('p')
                ->select('p')
                ->orderBy('p.codebarre')
            ;
        if($term){
            $qb
            ->where('p.codebare like ?1')
            ->setParameter(1, '%'.$term.'%')
            ;
        }
        return $this->paginate($qb, $limit, $offset);
    }

    /**
     * @return Product[] Returns an array of Product objects
     */
    public function findByCodebare($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.codebarre = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByProductPeriodAndStatut($date, $statut)
    {
        if ($statut == "open") {
            return $this->createQueryBuilder('p')
                ->join('p.stock', 's')
                ->addSelect('s')
                ->leftJoin("p.billDetails", "bd", "WITH", "bd.rs = 0", "bd.id")
                ->addSelect("bd")
                ->where("p.moveStatus = 1")
                ->andWhere("DATE_FORMAT(s.created, '%Y%m%d') < DATE_FORMAT(:date, '%Y%m%d')")
                ->andWhere("(s.available = 1 OR (s.available = 0 AND DATE_FORMAT(bd.created,'%Y%m%d') >= DATE_FORMAT(:date, '%Y%m%d')) AND bd.rs = 0)")
                ->setParameter('date', $date)
                ->getQuery()
                ->getResult();
        }
        if ($statut == "added") {
            return $this->createQueryBuilder('p')
                ->join('p.stock', 's')
                ->addSelect('s')
                ->where("p.moveStatus = 1")
                ->andWhere("DATE_FORMAT(s.created, '%Y%m%d') = DATE_FORMAT(:date, '%Y%m%d')")
                ->setParameter('date', $date)
                ->getQuery()
                ->getResult();
        }
        if ($statut == "selled") {
            return $this->createQueryBuilder('p')
                ->join("p.billDetails", "bd")
                ->addSelect("bd")
                ->where("p.moveStatus = 1")
                ->andWhere("DATE_FORMAT(bd.created, '%Y%m%d') = DATE_FORMAT(:date, '%Y%m%d')")
                ->setParameter('date', $date)
                ->getQuery()
                ->getResult();
        }
        if ($statut == "closed") {
            return $this->createQueryBuilder('p')
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

    /**
     * @return Product[] Returns an array of Product objects
     */
    public function findByDate($date)
    {
        return $this->createQueryBuilder('p')
            ->andWhere("DATE_FORMAT(p.created,'%Y-%m-%d') = :val")
            ->setParameter('val', $date)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return Product[] Returns an array of Product objects
     */
    public function findByInStock($value)
    {
        return $this->createQueryBuilder('p')
            ->where('p.moveStatus = 0')
            ->andWhere('p.codebarre = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return Product[] Returns an array of Product objects
     */
    public function findByStock()
    {
        return $this->createQueryBuilder('p')
            ->join('p.stock','s')
            ->where('p.moveStatus = 1')
            ->groupBy('p.id')
            ->orderBy('p.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return Product[] Returns an array of Product objects
     */
    public function findByStockAvailable()
    {
        return $this->createQueryBuilder('p')
            ->join('p.stock','s')
            ->where('p.moveStatus = 1')
            ->andWhere('s.available = 1')
            ->groupBy('p.id')
            ->orderBy('p.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
    
    /**
     * @param integer $type -- the type to find available products
     * @return Product[] Returns an array of Product objects
     */
    public function findByTypeStockAvailable($type)
    {
        return $this->createQueryBuilder('p')
            ->join('p.stock','s')
            ->addSelect('s')
            ->join('p.type','t','WITH','t.id = :type')
            ->addSelect('t')
            ->where('p.moveStatus = 1')
            ->andWhere('s.available = 1')
            ->andWhere('p.sold is null')
            ->groupBy('p.id')
            ->orderBy('p.id', 'ASC')
            ->setParameter('type',$type)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @param integer $type -- the type to find available products
     * @return Product[] Returns an array of Product objects
     */
    public function findByDelivery()
    {
        return $this->createQueryBuilder('p')
            ->addSelect("DATE_FORMAT(p.created,'%Y-%m-%d') as id,DATE_FORMAT(p.created,'%Y-%m-%d') as date,
            COUNT(p) as total, SUM(p.pu+p.caa) as pat, SUM(p.pv) as sell, p.codeLivraison")
            ->where('p.moveStatus = 1 OR p.moveStatus = 0')
            ->groupBy("date")
            ->orderBy("date", 'DESC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @param integer $type -- the type to find available products
     * @return Product[] Returns an array of Product objects
     */
    public function findByOneDelivery($date)
    {
        return $this->createQueryBuilder('p')
            ->addSelect("DATE_FORMAT(p.created,'%Y-%m-%d') as id,DATE_FORMAT(p.created,'%Y-%m-%d') as date,
            COUNT(p) as total, SUM(p.pu+p.caa) as pat, SUM(p.pv) as sell, p.code_livraison")
            ->where("p.moveStatus = 1 OR p.moveStatus = 0 AND 
            DATE_FORMAT(p.created,'%Y-%m-%d') = :date ")
            ->setParameter('date', $date)
            ->groupBy("date")
            ->getQuery()
            ->getResult()
        ;
    }

    // /**
    //  * @return Product[] Returns an array of Product objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Product
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
