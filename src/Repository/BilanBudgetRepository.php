<?php

namespace App\Repository;

use App\Entity\BilanBudget;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method BilanBudget|null find($id, $lockMode = null, $lockVersion = null)
 * @method BilanBudget|null findOneBy(array $criteria, array $orderBy = null)
 * @method BilanBudget[]    findAll()
 * @method BilanBudget[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BilanBudgetRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BilanBudget::class);
    }

    public function findOneWithBudgetAndDate($id, $date): ?BilanBudget
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.id = :id and b.month = MONTH(:date) and b.year = YEAR(:date)')
            ->setParameter('id', $id)->setParameter('date', $date)
            ->getQuery()
            ->getOneOrNullResult();
    }

    // /**
    //  * @return BilanBudget[] Returns an array of BilanBudget objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?BilanBudget
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
