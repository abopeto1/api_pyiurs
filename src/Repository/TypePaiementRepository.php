<?php

namespace App\Repository;

use App\Entity\TypePaiement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method TypePaiement|null find($id, $lockMode = null, $lockVersion = null)
 * @method TypePaiement|null findOneBy(array $criteria, array $orderBy = null)
 * @method TypePaiement[]    findAll()
 * @method TypePaiement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TypePaiementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TypePaiement::class);
    }

    /**
     * @return TypePaiement[] Returns an array of TypePaiement objects
     */
    public function findByDate($value)
    {
      return $this->createQueryBuilder('t')
          ->setParameter('date', $value)
          ->orderBy('t.id', 'ASC')
          ->join('t.bills','b')
          ->addSelect('b')
          ->where("DATE_FORMAT(:date,'%Y%m%d') = DATE_FORMAT(b.created,'%Y%m%d')")
          ->getQuery()
          ->getResult()
      ;
    }

    // /**
    //  * @return TypePaiement[] Returns an array of TypePaiement objects
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
    public function findOneBySomeField($value): ?TypePaiement
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
