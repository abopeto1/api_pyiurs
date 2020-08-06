<?php

namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Entity\Product;
use App\Entity\ProductStock;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

final class ProductDataPersister implements ContextAwareDataPersisterInterface
{
    private $_entity_manager;

    private $_request;

    public function __construct(EntityManagerInterface $entityManager, RequestStack $request){
        $this->_entity_manager = $entityManager;
        $this->_request = $request;
    }

    public function supports($data, array $context=[]): bool
    {
        return $data instanceof Product;
    }

    public function persist($data, array $context = [])
    {
        if ($this->_request->getCurrentRequest()->getMethod() === 'POST') {
            $data
                ->setCreated(new \DateTime());
            if($data->getType()->getSegment()->getDepartment()->getId() == 2){
                $stock = new ProductStock();
                $stock
                    ->setCreated(new \DateTime())
                    ->setQte(0)
                    ->setOutQte(0)
                    ->setAvailable(false)
                ;
                $data
                ->setStock($stock)
                ->setMoveStatus(1);
            }
            if($data->getType()->getSegment()->getDepartment()->getId() == 3){
                $data
                    ->setMoveStatus(1);
            }
        }
        $this->_entity_manager->persist($data);
        $this->_entity_manager->flush();
        return $data;
    }

    public function remove($data, array $context = [])
    {

    }
}
