<?php

namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Entity\Product;
use App\Entity\ProductMovement;
use App\Entity\ProductSample;
use App\Entity\ProductStock;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

final class ProductSampleDataPersister implements ContextAwareDataPersisterInterface
{
    private $_entity_manager;

    private $_request;

    public function __construct(EntityManagerInterface $entityManager, RequestStack $request){
        $this->_entity_manager = $entityManager;
        $this->_request = $request;
    }

    public function supports($data, array $context=[]): bool
    {
        return $data instanceof ProductSample;
    }

    public function persist($data, array $context = [])
    {
        if ($this->_request->getCurrentRequest()->getMethod() === 'POST') {
            $samples = $data->getProduct()->getSamples();
            $stock = $data->getProduct()->getStock();
            $movement = new ProductMovement();

            $sample = null;

            // Found last available sample
            foreach($samples as $s)
            {
                if($s->getAvailable())
                {
                    $sample = $s;
                    break;
                }
            }

            // If last sample exist set it to false 
            if($sample){
                $sample
                    ->setEnded(new \DateTime())
                    ->setAvailable(false)
                ;
                $this->_entity_manager->persist($sample);
            }

            // set outQte + 1
            $stock->setOutQte( $stock->getOutQte() + 1);
            $this->_entity_manager->persist($stock);

            // Log Movement
            $movement
                ->setProduct($data->getProduct())
                ->setType(ProductMovement::ADD_SAMPLE_PRODUCT)
                ->setQte(1)
                ->setCreated(new \DateTime())
                ->setDeliveryCode(null);
            $this->_entity_manager->persist($movement);

            $data
                ->setCreated(new \DateTime())
                ->setAvailable(true)
            ;
        }
        $this->_entity_manager->persist($data);
        $this->_entity_manager->flush();
        return $data;
    }

    public function remove($data, array $context = [])
    {

    }
}
