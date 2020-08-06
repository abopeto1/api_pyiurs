<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\View\ViewHandlerInterface;
use FOS\RestBundle\View\View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use App\Entity\Service;
use App\Entity\Taux;
use App\Form\ServiceType;
use Symfony\Component\HttpFoundation\Request;

class ServiceController extends AbstractController
{
    /**
     * @Rest\Get("/services")
     * @Rest\QueryParam(name="date",description="Get on a period")
     * @Rest\QueryParam(name="month",description="Get on a period")
     */
    public function index(ViewHandlerInterface $handler, ParamFetcher $paramFetcher)
    {
        $date = $paramFetcher->get('date');
        $month = $paramFetcher->get('month');
        $services = null;
        if(!empty($date)){
            $services = $this->getDoctrine()->getRepository(Service::class)->findWithDate($date);
        } else if (!empty($month)) {
            $services = $this->getDoctrine()->getRepository(Service::class)->findWithMonth($month);
        } else {
            $services = $this->getDoctrine()->getRepository(Service::class)->findAll();
        }
        $view = View::create();
        $view->setData($services);
        $view->getContext()->setGroups(array(
            'service'
        ));
        $view->setHeader('Access-Control-Allow-Origin', '*');
        return $handler->handle($view);
    }

    /**
     * @Rest\Post("/service")
     * 
     */
    public function create(ViewHandlerInterface $handler, Request $request){
        $service = new Service();
        $form = $this->createForm(ServiceType::class, $service);
        $form->submit($request->request->all());
        if ($form->isValid()) {
            $taux = $this->getDoctrine()->getRepository(Taux::class)->find(1);
            $service->setCreated(new \DateTime());
            $service->setTaux($taux->getTaux());
            $em = $this->getDoctrine()->getManager();
            $em->persist($service);
            $em->flush();
            $view = View::create();
            $view->setData($service);
            $view->getContext()->setGroups(array(
                'service'
            ));
            $view->setHeader('Access-Control-Allow-Origin', '*');
            return $handler->handle($view);
        } else {
            return $form;
        }
    }
}
