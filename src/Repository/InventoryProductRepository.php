<?php

namespace App\Repository;

use App\Entity\InventoryProduct;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method InventoryProduct|null find($id, $lockMode = null, $lockVersion = null)
 * @method InventoryProduct|null findOneBy(array $criteria, array $orderBy = null)
 * @method InventoryProduct[]    findAll()
 * @method InventoryProduct[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InventoryProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InventoryProduct::class);
    }

    /**
     * @return InventoryProduct[] Returns an array of InventoryProduct objects
     */
    public function findWithCodebarreAndType(string $codebarre, int $idInventory, array $types)
    {
        return $this->createQueryBuilder('i')
            ->join('i.product','p') ->addSelect('p')
            ->join('p.type','t')->addSelect('t')
            ->join('i.inventory', 'inv', 'WITH', 'inv.id = :idInventory')->addSelect('inv')
            ->andWhere(' p.codebarre = :codebarre')->andWhere('t.id in (:types)')
            ->setParameter('codebarre', $codebarre)->setParameter('idInventory', $idInventory)
            ->setParameter('types', $types)
            ->getQuery()
            ->getResult()
        ;
    }

    // /**
    //  * @return InventoryProduct[] Returns an array of InventoryProduct objects
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
    public function findOneBySomeField($value): ?InventoryProduct
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
