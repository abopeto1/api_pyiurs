<?php

namespace App\Repository;

use App\Entity\Customer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Customer|null find($id, $lockMode = null, $lockVersion = null)
 * @method Customer|null findOneBy(array $criteria, array $orderBy = null)
 * @method Customer[]    findAll()
 * @method Customer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CustomerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Customer::class);
    }

    /**
     * @return Customer[] Returns an array of Customer objects
     */
    public function findByFilter($start, $end, $filter)
    {
        if($filter == 'created'){
            return $this->createQueryBuilder('c')
                ->where("DATE_FORMAT(c.created,'%Y-%m-%d') >= :start AND DATE_FORMAT(c.created,'%Y-%m-%d') <= :end")
                ->setParameter('start',$start)
                ->setParameter('end',$end)
                ->orderBy('c.name', 'ASC')
                ->getQuery()
                ->getResult();
        }

        if($filter == 'bill_created'){
            return $this->createQueryBuilder('c')
                ->join('c.bills', 'b', 'WITH', "DATE_FORMAT(b.created,'%Y%m%d') >= DATE_FORMAT(:start,'%Y%m%d') AND DATE_FORMAT(b.created,'%Y%m%d') <= DATE_FORMAT(:end,'%Y%m%d')", "b.id")
                ->addSelect('b')
                ->setParameter('start', $start)
                ->setParameter('end', $end)
                ->orderBy('c.name', 'ASC')
                ->getQuery()
                ->getResult();
        }
    }

    /**
     * @return Customer[] Returns an array of Customer objects
     */
    public function findWithBillLoan($reports, $start, $end)
    {
        if($reports){
            return $this->createQueryBuilder('c')
                ->join('c.bills', 'b', 'WITH', 'b.reste > 0')
                ->addSelect('b')
                ->where("DATE_FORMAT(b.created,'%Y%m%d') >= DATE_FORMAT(:start,'%Y%m%d') 
                AND DATE_FORMAT(b.created,'%Y%m%d') <= DATE_FORMAT(:end,'%Y%m%d')")
                ->setParameter('start', $start)
                ->setParameter('end', $end)
                ->orderBy('c.name', 'ASC')
                ->getQuery()
                ->getResult();
        }
        return $this->createQueryBuilder('c')
            ->join('c.bills','b','WITH','b.reste > 0')
            ->addSelect('b')
            ->orderBy('c.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Customer[] Returns an array of Customer objects
     */
    public function findAllOrderByName()
    {
        return $this->createQueryBuilder('c')
            ->orderBy('c.name', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return Customer[] Returns an array of Customer objects
     */
    public function findByClotureMonth($value)
    {
        return $this->createQueryBuilder('c')
              ->setParameter('id', $value)
              ->orderBy('c.id', 'ASC')
              ->leftJoin('c.bills','b','WITH',"b.clotureMonth = :id",'b.id')
              ->addSelect('b')
              // ->leftJoin('ea.budgets','b','WITH',"YEAR(:date) = b.year AND MONTH(:date) = b.month",'b.id')
              // ->leftJoin('ea.expences','ex','WITH',"ex.clotureMonth = :id",'ex.id')
              // ->addSelect('ex')
              ->getQuery()
              ->getResult()
        ;
    }

    /**
     * @return Customer[] Returns an array of Customer objects
     */
    public function findByMonth($value)
    {
        return $this->createQueryBuilder('c')
            ->setParameter('value', $value)
            ->orderBy('c.id', 'ASC')
            ->leftJoin('c.bills', 'b', 'WITH', "DATE_FORMAT(b.created,'%Y%m') = DATE_FORMAT(:value,'%Y%m')", 'b.id')
            ->addSelect('b')
            // ->leftJoin('ea.budgets','b','WITH',"YEAR(:date) = b.year AND MONTH(:date) = b.month",'b.id')
            // ->leftJoin('ea.expences','ex','WITH',"ex.clotureMonth = :id",'ex.id')
            // ->addSelect('ex')
            ->getQuery()
            ->getResult();
    }

    // /**
    //  * @return Customer[] Returns an array of Customer objects
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
    public function findOneBySomeField($value): ?Customer
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
