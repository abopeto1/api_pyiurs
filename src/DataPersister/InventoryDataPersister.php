<?php

namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Entity\Inventory;
use App\Entity\InventoryProduct;
use App\Service\ProductService;
use Doctrine\ORM\EntityManagerInterface;

final class InventoryDataPersister implements ContextAwareDataPersisterInterface
{
    private $_entity_manager;
    private $_product_service;

    public function __construct(EntityManagerInterface $entityManager, ProductService $productService)
    {
        $this->_entity_manager = $entityManager;
        $this->_product_service = $productService;
    }

    public function supports($data, array $context=[]): bool
    {
        return $data instanceof Inventory;
    }

    public function persist($data, array $context = [])
    {
        $data->setCreated(new \DateTime());
        $products = $this->_product_service->getAvailableProducts();

        foreach($products as $product)
        {
            $inventoryProduct = new InventoryProduct();
            $inventoryProduct
                ->setInventory($data)
                ->setProduct($product)
                ->setStatus(false)
            ;

            $this->_entity_manager->persist($inventoryProduct);
        }

        $this->_entity_manager->persist($data);
        $this->_entity_manager->flush();
        return $data;
    }

    public function remove($data, array $context = [])
    {
        foreach($data->getInventoryProducts() as $inventoryProduct)
        {
            $this->_entity_manager->remove($inventoryProduct);
        }

        $this->_entity_manager->remove($data);
        
        $this->_entity_manager->flush();
    }
}
