<?php

namespace App\Repository;

use App\Entity\Expence;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Expence|null find($id, $lockMode = null, $lockVersion = null)
 * @method Expence|null findOneBy(array $criteria, array $orderBy = null)
 * @method Expence[]    findAll()
 * @method Expence[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ExpenceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Expence::class);
    }

    /**
     * @return Expence[] Returns an array of Expence objects
     */

    public function findByMonth($value)
    {
        $qb = $this->createQueryBuilder('e');
        $cond1 = $qb->expr()->andX(
            $qb->expr()->like("DATE_FORMAT(e.created,'%Y-%m')","DATE_FORMAT(:val,'%Y-%m')"),
            $qb->expr()->isNull("e.periode")
          );
        $cond2 = $qb->expr()->andX(
            $qb->expr()->like("e.periode","DATE_FORMAT(:val,'%Y-%m')")
          );
        return $qb
            ->where($qb->expr()->orX(
                $cond1,$cond2
              ))
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return Expence[] Returns an array of Expence objects
     */

    public function findByMonthAndExpenceCompte($date, $compte)
    {
        $qb = $this->createQueryBuilder('e')->join('e.expenceCompte','ec');
        $cond1 = $qb->expr()->andX(
            $qb->expr()->like("DATE_FORMAT(e.created,'%Y-%m')","DATE_FORMAT(:val,'%Y-%m')"),
            $qb->expr()->isNull("e.periode")
          );
        $cond2 = $qb->expr()->andX(
            $qb->expr()->like("e.periode","DATE_FORMAT(:val,'%Y-%m')")
          );
        return $qb
            ->where($qb->expr()->orX(
                $cond1,$cond2
              ))
            ->andWhere("ec.id = :compte")
            ->setParameter('val',$date)
            ->setParameter('compte',$compte)
            ->orderBy('e.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return Expence[] Returns an array of Expence objects
     */
    public function findByIntervalCreated($start,$end)
    {
        return $this->createQueryBuilder('e')
            ->andWhere("DATE_FORMAT(e.created,'%Y-%m-%d') >= :start AND DATE_FORMAT(e.created,'%Y-%m-%d') <= :end")
            ->setParameter('start', $start)
            ->setParameter('end', $end)
            ->orderBy('e.id', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return Expence[] Returns an array of Expence objects
     */
    public function findByDate($date)
    {
        return $this->createQueryBuilder('e')
            ->where("DATE_FORMAT(e.created,'%Y%m%d') = DATE_FORMAT(CURRENT_TIMESTAMP(),'%Y%m%d')")
            ->orderBy('e.id', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }
    // /**
    //  * @return Expence[] Returns an array of Expence objects
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
    public function findOneBySomeField($value): ?Expence
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
