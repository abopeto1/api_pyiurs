<?php

namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Entity\Bill;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;

final class BillDataPersister implements ContextAwareDataPersisterInterface
{
    private $_entity_manager;

    public function __construct(EntityManagerInterface $entityManager){
        $this->_entity_manager = $entityManager;
    }

    public function supports($data, array $context=[]): bool
    {
        return $data instanceof Bill;
    }

    public function persist($data, array $context = [])
    {
        $numero = $this->setNumberBill($data->getTypePaiement()->getId());

        $data
            ->setCreated(new \DateTime)
            ->setNumero($numero);

        if($data->getTypePaiement()->getId() !== 3){
            foreach($data->getBillDetails() as $billDetail)
            {
                $billDetail->setCreated(new \DateTime());
                $this->_entity_manager->persist($billDetail);

                if ($billDetail->getProduct()->getPv() - $billDetail->getNet() <= 2) {
                    $point = ($billDetail->getNet() / 10) * $billDetail->getQte();
                    $billDetail->setPoint($point);
                    $customer = $data->getCustomer();
                    $customer->setPoints($data->getCustomer()->getPoints() + $point);
                } else {
                    $billDetail->setPoint(0);
                }

                $stock = $billDetail->getProduct()->getStock();

                if($stock->getAvailableQte() < $billDetail->getQte())
                {
                    return new Response("Stock not Available", Response::HTTP_BAD_REQUEST);
                }
                
                $outQte = $stock->getOutQte() + $billDetail->getQte();
                $stock->setOutQte( $outQte);

                $this->_entity_manager->persist($stock);
                if($stock->getAvailableQte() < 1){
                    $stock->setAvailable(false);
                }
            }
        }

        if(3 === $data->getTypePaiement()->getId())
        {
            $billReference = $data->getBillReference();
            if($data->getNet() > $billReference->getReste())
            {
                return new Response("Net lager than reste", Response::HTTP_BAD_REQUEST);
            }

            if(0 >= $data->getNet())
            {
                return new Response("Net don't be equal or lower to 0", Response::HTTP_BAD_REQUEST);
            }

            $reste = $billReference->getReste() - $data->getNet();
            $billReference->setReste($reste);

            $this->_entity_manager->persist($billReference);
        }
        
        $this->_entity_manager->persist($data);

        // Apply the persistence
        $this->_entity_manager->flush();
        return $data;
    }

    public function remove($data, array $context = [])
    {

    }

    private function setNumberBill($typePaiement){
        if($typePaiement === 1 || $typePaiement === 2){
            $last = $this->_entity_manager->getRepository(Bill::class)->getLastNumber('FCT00');
            $next = $last['last_id'] + 1;
            return 'FCT000'.$next;
        } else if($typePaiement === 3){
            $last = $this->_entity_manager->getRepository(Bill::class)->getLastNumber('CRD00');
            $next = $last['last_id'] + 1;
            return 'CRD000'.$next;
        } else if($typePaiement === 4){
            $last = $this->_entity_manager->getRepository(Bill::class)->getLastNumber('EXC00');
            $next = $last['last_id'] + 1;
            return 'EXC000'.$next;
        }
  }
}
