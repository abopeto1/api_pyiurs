<?php

namespace App\Repository;

use App\Entity\CustomerStatusLog;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method CustomerStatusLog|null find($id, $lockMode = null, $lockVersion = null)
 * @method CustomerStatusLog|null findOneBy(array $criteria, array $orderBy = null)
 * @method CustomerStatusLog[]    findAll()
 * @method CustomerStatusLog[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CustomerStatusLogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CustomerStatusLog::class);
    }

    // /**
    //  * @return CustomerStatusLog[] Returns an array of CustomerStatusLog objects
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
    public function findOneBySomeField($value): ?CustomerStatusLog
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
