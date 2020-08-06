<?php

namespace App\Repository;

use App\Entity\Inventory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Inventory|null find($id, $lockMode = null, $lockVersion = null)
 * @method Inventory|null findOneBy(array $criteria, array $orderBy = null)
 * @method Inventory[]    findAll()
 * @method Inventory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InventoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Inventory::class);
    }

    /**
     * @return Inventory[] Returns an array of Inventory objects
     */
    public function findAllAsc()
    {
        return $this->createQueryBuilder('i')
            ->orderBy('i.created', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findWithProductCodebarreAndType(int $idInventory, string $codebarre, array $types)
    {
        return $this->createQueryBuilder('i')
            ->join('i.inventoryProducts','ip')->addSelect('ip')
            ->join('ip.product', 'p')->addSelect('p')
            ->join('p.type','t')->addSelect('t')
            ->andWhere(' p.codebarre = :codebarre')->andWhere('t.id in (:types)')->andWhere('i.id = :idInventory')
            ->setParameter('codebarre', $codebarre)->setParameter('idInventory', $idInventory)
            ->setParameter('types', $types)
            ->getQuery()
            ->getOneOrNullResult();
    }
    // /**
    //  * @return Inventory[] Returns an array of Inventory objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Inventory
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
