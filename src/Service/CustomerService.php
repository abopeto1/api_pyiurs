<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\CustomerStatusLog;

class CustomerService
{
    private $_em;
    private $_repository;
    
    public function __construct(EntityManagerInterface $em){
        $this->_em = $em;
        $this->_repository = $this->_em->getRepository('App:Customer');
    }

    public function refreshCustomerCategory(){
        $customers = $this->_repository->findAll();
        $categories = $this->_em->getRepository('App:CustomerCategorie')->findAll();
        
        foreach($customers as $customer){
            $actualCategory = $customer->getCategorie()->getId();
            $bills = $customer->getBills()->filter(function($bill){
                $lastMonth = date("Ym", \strtotime("first day of previous month"));
                if($bill->getCreated()->format('Ym') == $lastMonth) return $bill;
            });
            $total = 0; $newCategory = 1; $status = "EQUAL";

            if(!$bills->isEmpty()){
                foreach($bills as $bill){
                    if($bill->getTypePaiement()->getId() == 2){
                        $total += $bill->getAccompte();
                    } else {
                        $total += $bill->getNet();
                    }
                }
                foreach($categories as $category){
                    if($total >= intval($category->getQuota())){
                        $newCategory = intval($category->getId());
                    }
                }
                if($newCategory > $actualCategory) $status = "UP";
                if($newCategory == $actualCategory) $status = "EQUAL";
                if($newCategory < $actualCategory) $status = "DOWN";

                $cat = $this->_em->getRepository('App:CustomerCategorie')->find($newCategory);
                
                $log = new CustomerStatusLog;
                $log->setCustomer($customer)->setCategory($cat)->setStatus($status)->setTotal($total);
                $this->_em->persist($log);
                $customer->setCategorie($cat);
                $this->_em->persist($customer);
            } else {
                if($newCategory > $actualCategory) $status = "UP";
                if($newCategory == $actualCategory) $status = "EQUAL";
                if($newCategory < $actualCategory) $status = "DOWN";
                
                $cat = $this->_em->getRepository('App:CustomerCategorie')->find($newCategory);
                
                $log = new CustomerStatusLog;
                $log->setCustomer($customer)->setCategory($cat)->setStatus($status)->setTotal($total);
                $this->_em->persist($log);
                $customer->setCategorie($cat);
                $this->_em->persist($customer);
            }
        }
        $this->_em->flush();
        return new Response("All customers categories are updated");
    }
}