<?php

namespace App\Repository;

use App\Entity\Bill;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use DoctrineExtensions\Query\Mysql\Day;

/**
 * @method Bill|null find($id, $lockMode = null, $lockVersion = null)
 * @method Bill|null findOneBy(array $criteria, array $orderBy = null)
 * @method Bill[]    findAll()
 * @method Bill[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BillRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Bill::class);
    }

    /**
     * we get the last bill number
     */
    public function getLastNumber($value)
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = "select MAX(cast(REPLACE(SynthID,'$value','') as signed)) last_id from sell_synth where SynthID like '%$value%'";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $result = $stmt->fetch();
    }

    /**
     * @param string the today date
     * @return Bill[] Returns an array of Bill objects
     */
    public function findByCreatedDay($val)
    {
        return $this->createQueryBuilder('b')
            ->where("DATE_FORMAT(b.created,'%Y%m%d') = DATE_FORMAT(:val,'%Y%m%d')")
            ->orderBy('b.id', 'DESC')
            ->setParameter('val',$val)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return BillDetails[] Returns an array of BillDetails objects
     */
    public function findByInterval($start,$end)
    {
        return $this->createQueryBuilder('b')
            ->where("DATE_FORMAT(b.created,'%Y-%m-%d') >= :start AND DATE_FORMAT(b.created,'%Y-%m-%d') <= :end")
            ->orderBy('b.id', 'DESC')
            ->setParameter('start',$start)->setParameter('end',$end)
            ->getQuery()
            ->getResult()
        ;
    }
    
    /**
     * @return Bill[] Returns an array of Bill objects
     */
    public function findByDate($date)
    {
        return $this->createQueryBuilder('b')
            ->where("DATE_FORMAT(b.created,'%Y%m%d') = DATE_FORMAT(CURRENT_TIMESTAMP(),'%Y%m%d')")
            ->orderBy('b.id', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return Bill[] Returns an array of Bill objects
     */
    public function findByMonth($date)
    {
        return $this->createQueryBuilder('b')
            ->where("DATE_FORMAT(b.created,'%Y%m') = DATE_FORMAT(:date,'%Y%m')")
            ->orderBy('b.id', 'DESC')
            ->setParameter('date', $date)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return Bill[] Returns an array of Bill objects
     */
    public function findByYear($year)
    {
        return $this->createQueryBuilder('b')
            ->where("YEAR(b.created) = :year")
            ->orderBy('b.created', 'DESC')
            ->setParameter('year', $year)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return Bill[] Returns an array of Bill objects
     */
    public function findBillsActualMonth()
    {
        return $this->createQueryBuilder('b')
            ->where('MONTH(b.created) = MONTH(CURRENT_TIMESTAMP())')
            ->andWhere('YEAR(b.created) = YEAR(CURRENT_TIMESTAMP())')
            ->orderBy('b.id', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return Bill[] Returns an array of Bill objects
     */
    public function findBillsCredit()
    {
        return $this->createQueryBuilder('b')
            ->join("b.typePaiement","tp")
            ->where('tp.id = 2')
            ->andWhere('b.reste > 0')
            ->orderBy('b.id', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    // /**
    //  * @return Bill[] Returns an array of Bill objects
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
    public function findOneBySomeField($value): ?Bill
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
