<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
// use Symfony\Bundle\FrameworkBundle\Annotations\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\View\ViewHandlerInterface;
use FOS\RestBundle\View\View;
use App\Entity\Taux;
use App\Form\TauxType;

class TauxController extends AbstractController
{
    /**
     * Get All billsdetails on interval
     * @Rest\Get("/taux", name="bill_details")
     */
    public function index(ViewHandlerInterface $handler)
    {
        $taux = $this->getDoctrine()->getRepository('App:Taux')->findAll();
        $view = View::create();
        $view->setData($taux);
        $view->setHeader('Access-Control-Allow-Origin', '*');
        return $handler->handle($view);
    }

    /**
     * Get All a taux
     * @Rest\Get("/taux/{id}", name="bill_details")
     */
    public function getOne(Request $request, ViewHandlerInterface $handler)
    {
        $taux = $this->getDoctrine()->getRepository('App:Taux')->find($request->get('id'));
        $view = View::create();
        $view->setData($taux);
        $view->setHeader('Access-Control-Allow-Origin', '*');
        return $handler->handle($view);
    }

    /**
     * update the taux
     * @Rest\Put("/taux/{id}")
     *
     * @return Response
     */
    public function updateTaux(Request $request, ViewHandlerInterface $handler)
    {
        $view = View::create();
        $em = $this->getDoctrine()->getManager();
        $taux = $this->getDoctrine()->getRepository(Taux::class)->find($request->get('id'));
        $form = $this->createForm(TauxType::class, $taux);
        $form->submit($request->request->all());
        if ($form->isValid()) {
            $em->merge($taux);
            $em->flush();
            $view->setData($taux);
            $view->setHeader('Access-Control-Allow-Origin', '*');
            return $handler->handle($view);
        } else {
            return $form;
        }
    }
}
