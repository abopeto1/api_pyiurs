<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Annotations\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\View\View;
use App\Service\ProductService;

class DeliveryController extends Controller
{
    /**
     * Get All Stock Log
     * @Rest\Get("/deliveries")
     * @Rest\QueryParam(name="date",description="Toutes les ventes du jour actuel")
     */
    public function getAll(ParamFetcher $paramFetcher, ProductService $productService)
    {
        $date = $paramFetcher->get('date');
        if(!empty($date)){
            $products = $this->getDoctrine()->getRepository('App:Product')->findByDate($date);
            $delivery = $productService->getOneDeliveryDate($date);
            $serializer = new \JMS\Serializer\SerializerBuilder;
            $p = $serializer::create()->build($products, 'json','products');
            $delivery['products'] = $p;
            return new JsonResponse($delivery);
        }

        $deliveries = $productService->getListDeliveryDate();
        return new JsonResponse($deliveries);
    }
}
