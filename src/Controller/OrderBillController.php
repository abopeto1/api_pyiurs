<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\ViewHandlerInterface;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use App\Form\OrderBillType;
use App\Entity\OrderBill;

class OrderBillController extends AbstractController
{
    /**
     * @Rest\Post("/order_bill")
     */
    public function index(ViewHandlerInterface $handler, Request $request)
    {
        $order_bill = new OrderBill();
        $form = $this->createForm(OrderBillType::class, $order_bill);
        $form->submit($request->request->all());
        if ($form->isValid()) {
            $order_bill->setCreated(new \DateTime());
            $em = $this->getDoctrine()->getManager();
            $em->persist($order_bill);
            $em->flush();
            $view = View::create();
            $view->setData($order_bill);
            return $handler->handle($view);
        } else {
            return $form;
        }
    }
}
