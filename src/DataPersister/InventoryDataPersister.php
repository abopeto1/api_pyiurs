<?php

namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Entity\Inventory;
use Doctrine\ORM\EntityManagerInterface;

final class InventoryDataPersister implements ContextAwareDataPersisterInterface
{
    private $_entity_manager;

    public function __construct(EntityManagerInterface $entityManager){
        $this->_entity_manager = $entityManager;
    }

    public function supports($data, array $context=[]): bool
    {
        return $data instanceof Inventory;
    }

    public function persist($data, array $context = [])
    {
        
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
