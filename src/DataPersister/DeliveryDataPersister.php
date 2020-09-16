<?php

/*
 * This file is part of the API for Pyiurs Boutique POS.
 *
 * (c) Arnold Bopeto <abopeto1@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Entity\Delivery;
use App\Entity\Product;
use App\Service\TypeService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;

final class DeliveryDataPersister implements ContextAwareDataPersisterInterface
{
    private $_entity_manager;

    private $_request;

    private $_typeService;

    public function __construct(EntityManagerInterface $entityManager, RequestStack $request, TypeService $typeService)
    {
        $this->_entity_manager = $entityManager;
        $this->_request = $request;
        $this->_typeService = $typeService;
    }

    public function supports($data, array $context=[]): bool
    {
        return $data instanceof Delivery;
    }

    public function persist($data, array $context = [])
    {
        if ($this->_request->getCurrentRequest()->getMethod() === 'POST') {
           // set created property
           $data->setCreated(new \DateTime());

           // persist products
           $i = 1;
           if($data->getProducts())
           {
               foreach($data->getProducts() as $product)
               {
                    // Get Type
                    $type = $this->_typeService->getProductType($product->getPostType(), $product->getSegment(), $i);

                    if($type instanceof Response){
                        return $type;
                    }
                    $product
                        ->setCreated(new \DateTime())
                        ->setMoveStatus(0)
                        ->setType($type)
                    ;
                    
                    // persist product
                    $this->_entity_manager->persist($product);
                    $i++;
               }
           }
        }
        $this->_entity_manager->persist($data);
        $this->_entity_manager->flush();
        return $data;
    }

    public function remove($data, array $context = [])
    {

    }
}
