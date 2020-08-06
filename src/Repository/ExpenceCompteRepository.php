<?php

namespace App\Repository;

use App\Entity\ExpenceCompte;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method ExpenceCompte|null find($id, $lockMode = null, $lockVersion = null)
 * @method ExpenceCompte|null findOneBy(array $criteria, array $orderBy = null)
 * @method ExpenceCompte[]    findAll()
 * @method ExpenceCompte[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ExpenceCompteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ExpenceCompte::class);
    }

    public function findByCompteAndMonth($compte, $date): ?ExpenceCompte
    {
        return $this->createQueryBuilder('e')
            ->join('e.expences','ex','WITH',"DATE_FORMAT(ex.created,'%Y%m') = DATE_FORMAT(:date,'%Y%m')",'ex.id')
            ->addSelect('ex')
            ->andWhere("e.id = :compte")
            ->setParameter('compte', $compte)->setParameter('date', $date)
            ->orderBy('e.id', 'ASC')
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    // /**
    //  * @return ExpenceCompte[] Returns an array of ExpenceCompte objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ExpenceCompte
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
