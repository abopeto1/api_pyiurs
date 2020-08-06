<?php

namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Entity\Segment;
use Doctrine\ORM\EntityManagerInterface;

final class SegmentDataPersister implements ContextAwareDataPersisterInterface
{
    private $_entity_manager;

    public function __construct(EntityManagerInterface $entityManager){
        $this->_entity_manager = $entityManager;
    }

    public function supports($data, array $context=[]): bool
    {
        return $data instanceof Segment;
    }

    public function persist($data, array $context = [])
    {
        $data
            ->setCreated(new \DateTime())
        ;

        $this->_entity_manager->persist($data);
        $this->_entity_manager->flush();
        return $data;
    }

    public function remove($data, array $context = [])
    {

    }
}
