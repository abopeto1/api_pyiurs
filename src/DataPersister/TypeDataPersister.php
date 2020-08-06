<?php

namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Entity\Type;
use Doctrine\ORM\EntityManagerInterface;

final class TypeDataPersister implements ContextAwareDataPersisterInterface
{
    private $_entity_manager;

    public function __construct(EntityManagerInterface $entityManager){
        $this->_entity_manager = $entityManager;
    }

    public function supports($data, array $context=[]): bool
    {
        return $data instanceof Type;
    }

    public function persist($data, array $context = [])
    {
        $tabs = explode(' ',$data->getName());
        $ref = "";

        foreach($tabs as $e){
            $ref .= strtoupper(substr($e, 0));
        }

        $data
            // ->setCreated(new \DateTime())
            ->setRefType($ref)
        ;

        $this->_entity_manager->persist($data);
        $this->_entity_manager->flush();
        return $data;
    }

    public function remove($data, array $context = [])
    {

    }
}
