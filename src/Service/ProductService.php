<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;

class  ProductService
{
    private $_em;
    
    public function __construct(EntityManagerInterface $em){
        $this->_em = $em;
    }

    public function getAvailableProduct()
    {
        $products = $this->_em->getRepository('App:Product')->findByStockAvailable();
        return $products;
    }

    /**
     * Return an array of the actual stock situation
     * @return array[]
    */
    public function getStockSituationActualDay($created){
        $products = $this->_em->getRepository('App:Product')->findByStock();
        $open = 0; $value_open = 0; $pat_value_open = 0;
        $added = 0; $returned = 0; $selled = 0; $closed = 0; $value_closed = 0; $pat_value_closed = 0;
        
        foreach($products as $p){
            $stock_date = intval($p->getStock()->getCreated()->format('Ymd'));
            $billDetail = null;
            if(count($p->getBillDetails()) > 0){
                foreach($p->getBillDetails() as $bd){
                    if(intval($bd->getCreated()->format('Ymd')) === intval($created->format('Ymd'))){
                        $billDetail = $bd;
                    }
                }
            }

            if(
                ($p->getStock()->getAvailable() && $stock_date < intval($created->format('Ymd'))) || (
                    !($p->getStock()->getAvailable()) && !empty($billDetail)
                )
            ){
                $open++;
                $value_open += $p->getPv();
                $pat_value_open += $p->getPu() + $p->getCaa();
            }
            if($stock_date === intval($created->format('Ymd'))){
                $added++;
            }
            if(!empty($billDetail)){
                $selled++;
            }
            if($p->getStock()->getAvailable()){
                $closed++;
                $value_closed += $p->getPv();
                $pat_value_closed += $p->getPu() + $p->getCaa();
            }
        }

        return [
            "id" => 0, 'created' => $created, "open" => $open, "added" => $added, "selled" => $selled,
            "closed" => $closed, "value_closed" => $value_closed, "pat_value_closed" => $pat_value_closed 
        ];
    }

    /**
     * Return an array of the actual stock situation
     * @return array[]
    */
    public function getStockSituationByDay($created){
        $products = $this->_em->getRepository('App:Product')->findByStock();
        $open = 0; $added = 0; $returned = 0; $selled = 0; $closed = 0; $value_closed = 0; $pat_value_closed = 0;
        
        foreach($products as $p){
            $stock_date = intval($p->getStock()->getCreated()->format('Ymd'));
            $billDetail = null; $bdclose = null;
            if(count($p->getBillDetails()) > 0){
                foreach($p->getBillDetails() as $bd){
                    if(intval($bd->getCreated()->format('Ymd')) === intval($created->format('Ymd'))){
                        $billDetail = $bd;
                    }
                    if(intval($bd->getCreated()->format('Ymd')) > intval($created->format('Ymd'))){
                        $billDetail = $bd;
                    }
                }
            }

            if($stock_date < intval($created->format('Ymd')) && (
                $p->getStock()->getAvailable() || (
                    !($p->getStock()->getAvailable()) && $billDetail !== null
                )
            )){
                $open++;
            }
            if($stock_date === intval($created->format('Ymd'))){
                $added++;
            }
            if($stock_date <= intval($created->format('Ymd')) && !empty($billDetail)){
                $selled++;
            }
            if(
                $stock_date <= intval($created->format('Ymd')) && (
                    $p->getStock()->getAvailable() || ($bdclose !== null && (!$p->getStock()->getAvailable()))
                )
            ){
                $closed++;
                $value_closed += $p->getPv();
                $pat_value_closed += $p->getPu() + $p->getCaa();
            }
        }

        return [
            "id" => 'today', 'created' => $created, "open" => $open, "added" => $added, "selled" => $selled,
            "closed" => $closed, "value_closed" => $value_closed, "pat_value_closed" => $pat_value_closed 
        ];
    }

    /**
     * Return an array of the actual stock situation
     * @return array[]
    */
    public function getListDeliveryDate(){
        $products = $this->_em->getRepository('App:Product')->findByDelivery();
        
        return $products;
    }

    /**
     * Return an array of the actual stock situation
     * @return object
    */
    public function getOneDeliveryDate($date){
        $delivery = $this->_em->getRepository('App:Product')->findByOneDelivery($date);
        
        return $delivery;
    }
}