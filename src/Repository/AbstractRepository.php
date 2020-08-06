<?php

namespace App\Repository;

use Doctrine\ORM\QueryBuilder;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

abstract class AbstractRepository extends ServiceEntityRepository {
    public function __construct(ManagerRegistry $registry, $entityClass)
    {
        parent::__construct($registry, $entityClass);
    }

    protected function paginate(QueryBuilder $qb, $limit=20, $offset=0){
        if(0 == $limit){
            throw new \LogicException('$limit & $offset must be greater than 0');
        }

        $pager = new Pagerfanta(new DoctrineORMAdapter($qb));
        $currentPage = ceil(($offset + 1) / $limit);
        $pager->setCurrentPage($currentPage)->setMaxPerPage((int) $limit);

        return $pager;
    }
}