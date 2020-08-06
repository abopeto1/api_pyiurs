<?php

namespace App\Repository;

use App\Entity\ExpenceCompteCategorie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method ExpenceCompteCategorie|null find($id, $lockMode = null, $lockVersion = null)
 * @method ExpenceCompteCategorie|null findOneBy(array $criteria, array $orderBy = null)
 * @method ExpenceCompteCategorie[]    findAll()
 * @method ExpenceCompteCategorie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ExpenceCompteCategorieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ExpenceCompteCategorie::class);
    }

    /**
     * @return ExpenceCompteCategorie[] Returns an array of ExpenceCompteCategorie objects
     */
    public function findByMonth($value)
    {
        return $this->createQueryBuilder('e')
            ->setParameter('date', $value)
            ->orderBy('e.id', 'ASC')
            ->leftJoin('e.expenceAccounts','ea')
            ->addSelect('ea')
            ->leftJoin('ea.expences','ex','WITH',
            "((DATE_FORMAT(:date,'%Y%m') = DATE_FORMAT(ex.created,'%Y%m')) AND ex.periode is null) OR ex.periode = DATE_FORMAT(:date,'%Y-%m')",
            'ex.id')
            ->addSelect('ex')
            ->leftJoin('ea.budgets','b','WITH',"YEAR(:date) = b.year AND MONTH(:date) = b.month",'b.id')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return ExpenceCompteCategorie[] Returns an array of ExpenceCompteCategorie objects
     */
    public function findByClotureMonth($value)
    {
      return $this->createQueryBuilder('e')
          ->setParameter('id', $value)
          ->orderBy('e.id', 'ASC')
          ->leftJoin('e.expenceAccounts','ea')
          ->addSelect('ea')
          // ->leftJoin('ea.budgets','b','WITH',"YEAR(:date) = b.year AND MONTH(:date) = b.month",'b.id')
          ->leftJoin('ea.expences','ex','WITH',"ex.clotureMonth = :id",'ex.id')
          ->addSelect('ex')
          ->getQuery()
          ->getResult()
      ;
    }

    // /**
    //  * @return ExpenceCompteCategorie[] Returns an array of ExpenceCompteCategorie objects
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
    public function findOneBySomeField($value): ?ExpenceCompteCategorie
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
