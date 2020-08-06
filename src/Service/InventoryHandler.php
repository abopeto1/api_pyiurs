<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use App\Service\ProductService;
use App\Entity\Inventory;
use App\Entity\InventoryProduct;

class InventoryHandler
{
    protected $_em;

    protected $_productService;

    public function __construct(EntityManagerInterface $em,ProductService $productService)
    {
        $this->_em = $em;
        $this->_productService = $productService;
    }

    public function createInventory()
    {
        $inventory = new Inventory();
        $inventory->setCreated(new \DateTime());
        $products = $this->_productService->getAvailableProduct();

        foreach ($products as $product) {
            $inventoryProduct = new InventoryProduct();
            $inventoryProduct
                        ->setProduct($product)
                        ->setInventory($inventory)
                        ->setStatus(false);
            $this->_em->persist($inventoryProduct);
        }
        $this->_em->persist($inventory);
        $this->_em->flush();
        
        return $inventory;
    }
}