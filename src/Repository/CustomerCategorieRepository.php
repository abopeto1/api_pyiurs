<?php

namespace App\Repository;

use App\Entity\CustomerCategorie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method CustomerCategorie|null find($id, $lockMode = null, $lockVersion = null)
 * @method CustomerCategorie|null findOneBy(array $criteria, array $orderBy = null)
 * @method CustomerCategorie[]    findAll()
 * @method CustomerCategorie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CustomerCategorieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CustomerCategorie::class);
    }

    /**
     * @return CustomerCategorie[] Returns an array of CustomerCategorie objects
     */
    public function findWithSellDay($date)
    {
        return $this->createQueryBuilder('c')
            ->addSelect('c')
            ->leftJoin('c.customers','cu')
            ->addSelect('cu')
            ->leftJoin('cu.bills','b','WITH',"DATE_FORMAT(b.created,'%Y%m%d') = DATE_FORMAT(:date,'%Y%m%d')", 'b.id')
            ->addSelect('b')
            // ->andWhere('c.exampleField = :val')
            ->setParameter('date', $date)
            ->orderBy('c.id', 'ASC')
            // ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return CustomerCategorie[] Returns an array of CustomerCategorie objects
     */
    public function findWithSellMonth($date)
    {
        return $this->createQueryBuilder('c')
            ->addSelect('c')
            ->leftJoin('c.customers', 'cu')
            ->addSelect('cu')
            ->leftJoin('cu.bills', 'b', 'WITH', "DATE_FORMAT(b.created,'%Y%m') = DATE_FORMAT(:date,'%Y%m')", 'b.id')
            ->addSelect('b')
            // ->andWhere('c.exampleField = :val')
            ->setParameter('date', $date)
            ->orderBy('c.id', 'ASC')
            // ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }
    // /**
    //  * @return CustomerCategorie[] Returns an array of CustomerCategorie objects
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
    public function findOneBySomeField($value): ?CustomerCategorie
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
