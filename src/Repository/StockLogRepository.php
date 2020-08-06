<?php

namespace App\Repository;

use App\Entity\StockLog;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method StockLog|null find($id, $lockMode = null, $lockVersion = null)
 * @method StockLog|null findOneBy(array $criteria, array $orderBy = null)
 * @method StockLog[]    findAll()
 * @method StockLog[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StockLogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StockLog::class);
    }

    /**
     * @return StockLog[] Returns an array of StockLog objects
     */
    public function findWithMonth($month)
    {
        return $this->createQueryBuilder('s')
            ->andWhere("DATE_FORMAT(s.created,'%Y%m') = DATE_FORMAT(:val,'%Y%m')")
            ->setParameter('val', $month)
            ->orderBy('s.created', 'ASC')
            // ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

    // /**
    //  * @return StockLog[] Returns an array of StockLog objects
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
    public function findOneBySomeField($value): ?StockLog
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
