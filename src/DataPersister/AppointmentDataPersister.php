<?php

namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Entity\Appointment;
use App\Orange\Api;
use Doctrine\ORM\EntityManagerInterface;

final class AppointmentDataPersister implements ContextAwareDataPersisterInterface
{
    private $_entity_manager;
    private $orangeApi;

    public function __construct(EntityManagerInterface $entityManager, Api $orangeApi){
        $this->_entity_manager = $entityManager;
        $this->orangeApi = $orangeApi;
    }

    public function supports($data, array $context=[]): bool
    {
        return $data instanceof Appointment;
    }

    public function persist($data, array $context = [])
    {
        $numero = $this->setNumber($data->getService());
        $data
            ->setCode($numero)
            ->setCreated(new \DateTime())
            ->setStatus("ON_WAITING")
        ;
        $this->_entity_manager->persist($data);
        $this->_entity_manager->flush();

        // Sending the appointment sms
        // $res = $this->orangeApi->postMessage($data->getCustomer());
        return $data;
    }

    public function remove($data, array $context = [])
    {

    }

    private function setNumber($service)
    {
        $numero = $service->getCodebarre(). date('YmjHis');
        return $numero;
    }
}
