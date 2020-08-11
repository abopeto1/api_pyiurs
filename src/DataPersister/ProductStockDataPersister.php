<?php

namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Entity\Product;
use App\Entity\ProductMovement;
use App\Entity\ProductStock;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

final class ProductStockDataPersister implements ContextAwareDataPersisterInterface
{
    private $_entity_manager;

    private $_request;

    public function __construct(EntityManagerInterface $entityManager, RequestStack $request){
        $this->_entity_manager = $entityManager;
        $this->_request = $request;
    }

    public function supports($data, array $context=[]): bool
    {
        return $data instanceof ProductStock;
    }

    public function persist($data, array $context = [])
    {
        if ($this->_request->getCurrentRequest()->getMethod() === 'PATCH') {
            $qte = $data->getAddStock() + $data->getQte();
            $available = $qte > 0;

            $data
                ->setQte($qte)
                ->setAvailable($available)
            ;

            $product = $this->_entity_manager->getRepository(Product::class)->findOneBy(array("stock" => $data->getId()));
            $movement = new ProductMovement();
            $movement
                    ->setProduct($product)
                    ->setType(ProductMovement::ADDING_PRODUCT)
                    ->setQte($data->getAddStock())
                    ->setCreated(new \DateTime())
                    ->setDeliveryCode($data->getDeliveryCode())
                    ;
            $this->_entity_manager->persist($movement);
        }

        $this->_entity_manager->persist($data);
        $this->_entity_manager->flush();
        return $data;
    }

    public function remove($data, array $context = [])
    {

    }
}
