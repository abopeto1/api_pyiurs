<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Annotations\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\View\View;
use FOS\RestBundle\View\ViewHandlerInterface;
use App\Entity\Type;

class TypeController extends AbstractController
{
    /**
     * List all types
     * @Rest\Get("/types")
     * @Rest\QueryParam(name="delivery_product_date",description="delivery")
     * @Rest\QueryParam(name="statut",description="statut")
     * @Rest\QueryParam(name="date",description="date")
     *
     * @return Response
     */
    public function index(ParamFetcher $paramFetcher, ViewHandlerInterface $handler)
    {
        $date = $paramFetcher->get('delivery_product_date');
        $created = $paramFetcher->get('date');
        $statut = $paramFetcher->get('statut');

        if(!empty($date)){
            $types = $this->getDoctrine()->getRepository(Type::class)->findByProductDate($date);
            $view = View::create();
            $view->setData($types);
            $view->getContext()->setGroups(array(
                "products"
            ));
            $view->setHeader('Access-Control-Allow-Origin','*');
            return $handler->handle($view);
        }
        if (!empty($created)) {
            $types = $this->getDoctrine()->getRepository(Type::class)->findByProductPeriodAndStatut($created, $statut);
            // $a = 0;
            // foreach ($types as $type) {
            //     $a += count($type->getProducts());
            // }
            // return $a;
            $view = View::create();
            $view->setData($types);
            $view->getContext()->setGroups(array(
                "type_stats"
            ));
            $view->setHeader('Access-Control-Allow-Origin', '*');
            return $handler->handle($view);
        }
        $types = $this->getDoctrine()->getRepository(Type::class)->findAll();
        $view = View::create();
        $view->setData($types);
        $view->getContext()->setGroups(array(
            "type"
        ));
        $view->setHeader('Access-Control-Allow-Origin','*');
        return $handler->handle($view);
    }
}
