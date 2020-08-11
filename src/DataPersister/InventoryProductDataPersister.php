<?php

namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Entity\InventoryProduct;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

final class InventoryProductDataPersister implements ContextAwareDataPersisterInterface
{
    private $_entity_manager;
    private $_request;

    public function __construct(EntityManagerInterface $entityManager, RequestStack $requestStack)
    {
        $this->_entity_manager = $entityManager;
        $this->_request = $requestStack;
    }

    public function supports($data, array $context=[]): bool
    {
        return $data instanceof InventoryProduct;
    }

    public function persist($data, array $context = [])
    {
        if ($this->_request->getCurrentRequest()->getMethod() === 'PATCH') {
            $data->setUpdated(new \DateTime());
        }

        $this->_entity_manager->persist($data);
        $this->_entity_manager->flush();
        return $data;
    }

    public function remove($data, array $context = [])
    {
        
    }
}
