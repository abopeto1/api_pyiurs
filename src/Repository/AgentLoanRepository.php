<?php

namespace App\Repository;

use App\Entity\AgentLoan;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method AgentLoan|null find($id, $lockMode = null, $lockVersion = null)
 * @method AgentLoan|null findOneBy(array $criteria, array $orderBy = null)
 * @method AgentLoan[]    findAll()
 * @method AgentLoan[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AgentLoanRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AgentLoan::class);
    }

    // /**
    //  * @return AgentLoan[] Returns an array of AgentLoan objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?AgentLoan
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
