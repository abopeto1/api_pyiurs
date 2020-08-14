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
        
        $message = $this->getMessage($data);
        // Sending the appointment sms
        $this->orangeApi->postMessage($data->getCustomer(), $message);
        return $data;
    }

    public function remove($data, array $context = [])
    {

    }

    private function setNumber($service)
    {
        $numero = 'RDV'.$service->getCodebarre().$service->getId();
        return $numero;
    }

    public function getMessage(Appointment $appointment)
    {
        $customer = $appointment->getCustomer();

        $message = 
        $customer->getName(). 
        ", Pyiurs Boutique vous souhaite la bienvenu(e), votre rendez vous est fixé à ".
        $appointment->getPlanned()->format('H:i');

        return $message;
        
    }
}
