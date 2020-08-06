<?php

namespace App\Controller;

use App\Entity\Expence;
use App\Entity\ExpenceCompte;
use App\Entity\OrderEcheance;
use App\Entity\Provider;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use FOS\RestBundle\View\ViewHandlerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class OrderEcheanceController extends AbstractController
{
    /**
     * @Rest\Put("/order_echeance/{id}")
     */
    public function updateOrderEcheance(Request $request, ViewHandlerInterface $handler)
    {
        $orderEcheance = $this->getDoctrine()->getRepository(OrderEcheance::class)->find($request->get('id'));
        if (!$orderEcheance) {
            return new Response("Order Echeance doesn't exist",Response::HTTP_BAD_REQUEST);
        }
        $provider = $this->getDoctrine()->getRepository(Provider::class)->find(12);
        $compte = $this->getDoctrine()->getRepository(ExpenceCompte::class)->find(2);
        $operator = $this->getDoctrine()->getRepository(User::class)->find($request->get('operator'));
        $em = $this->getDoctrine()->getManager();
        $date = new \DateTime();
        $expence = new Expence();
        $expence->setCode('TRANS_'. $date->format('Ymd_H:i'))
        ->setMotif("Paiement Echeance du ". $orderEcheance->getPaied()->format('Y-m-d') ." - Commande ". $orderEcheance->getTheOrder()->getCode())
        ->setTaux($orderEcheance->getTheOrder()->getTaux())->setCurrency($orderEcheance->getTheOrder()->getCurrency())
        ->setCreated($date)->setStatut(true)->setProvider($provider)->setMontant($orderEcheance->getAmount())
        ->setExpenceCompte($compte)->setOperator($operator)->setValidated($date);
        $em->persist($expence);
        $orderEcheance->setExpence($expence);
        $em->persist($orderEcheance);
        $em->flush();

        $view = View::create();
        $view->setData($orderEcheance);
        $view->getContext()->setGroups(array(
            'order_echeance'
        ));
        return $handler->handle($view);
    }
}
