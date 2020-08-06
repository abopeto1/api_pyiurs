<?php

namespace App\Repository;

use App\Entity\BilanCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method BilanCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method BilanCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method BilanCategory[]    findAll()
 * @method BilanCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BilanCategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BilanCategory::class);
    }

    /**
     * @return BilanCategory[] Returns an array of ExpenceCompteCategorie objects
     */
    public function findWithDate($value)
    {
        return $this->createQueryBuilder('b')
            // ->setParameter('date', $value)
            // ->orderBy('e.id', 'ASC')
            // ->leftJoin('e.expenceAccounts', 'ea')
            // ->addSelect('ea')
            // ->leftJoin('ea.bilanBudgets', 'ex', 'WITH', "DATE_FORMAT(:date,'%Y%m') = DATE_FORMAT(ex.created,'%Y%m')", 'ex.id')
            // ->addSelect('ex')
            // ->leftJoin('ea.budgets', 'b', 'WITH', "YEAR(:date) = b.year AND MONTH(:date) = b.month", 'b.id')
            ->getQuery()
            ->getResult();
    }
    // /**
    //  * @return BilanCategory[] Returns an array of BilanCategory objects
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
    public function findOneBySomeField($value): ?BilanCategory
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
