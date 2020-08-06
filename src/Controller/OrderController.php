<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\ViewHandlerInterface;
use FOS\RestBundle\View\View;
use App\Entity\Order;
use App\Form\OrderType;
use App\Form\OrderEcheanceType;

class OrderController extends AbstractController
{
    /**
     * @Rest\Get("/orders")
     */
    public function index(ViewHandlerInterface $handler)
    {
        $orders = $this->getDoctrine()->getRepository(Order::class)->findAll();
        $view = View::create();
        $view->setData($orders);
        $view->getContext()->setGroups(array(
            'order'
        ));
        $view->setHeader('Access-Control-Allow-Origin', '*');
        return $handler->handle($view);
    }

    /**
     * @Rest\Get("/order/{id}")
     */
    public function getOne(ViewHandlerInterface $handler, Request $request)
    {
        $order = $this->getDoctrine()->getRepository(Order::class)->find($request->get('id'));
        $view = View::create();
        $view->setData($order);
        $view->getContext()->setGroups(array(
            'order'
        ));
        $view->setHeader('Access-Control-Allow-Origin', '*');
        return $handler->handle($view);
    }

    /**
     * @Rest\Post("/order")
     */
    public function create(Request $request){
        $order = new Order(); 
        $code = "Order-".date('YmdHi');
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(OrderType::class, $order);
        $form->submit($request->request->all());
        if ($form->isValid()) {
            
            $em = $this->getDoctrine()->getManager();
            $order->setCode($code)->setCreated(new \DateTime("Africa/Kinshasa"))->setDeliveried(false);
            if (count($order->getOrderEcheances()) > 0) {
                foreach ($order->getOrderEcheances() as $echeance) {
                    $echeance->setTheOrder($order);
                    $em->persist($echeance);
                }
            }
            $em->persist($order);
            $em->flush();
            return $order;
        } else {
            return $form;
        }
        
    }

    /**
     * @Rest\Put("/order/{id}")
     * @ParamConverter("order", converter="fos_rest.request_body")
     */
    public function update(Order $order)
    {
        $em = $this->getDoctrine()->getManager();
        $em->merge($order);
        $em->flush();
        return $order;
    }
}
