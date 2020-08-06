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
use App\Form\AgentType;

class AgentController extends AbstractController
{
    /**
     * List all customers
     * @Rest\Get("/agents")
     *
     * @return Response
     */
    public function getAgents(ViewHandlerInterface $handler)
    {
        $agents = $this->getDoctrine()->getRepository("App:Agent")->findAll();
        $view = View::create();
        $view->setData($agents);
        $view->getContext()->setGroups(array(
            "agent"
        ));
        $view->setHeader('Access-Control-Allow-Origin', '*');
        return $handler->handle($view);
    }

    /**
     * create an agent
     * @Rest\Post("/agent")
     *
     * @return Response
     */
    public function createCustomerAction(Request $request, ViewHandlerInterface $handler)
    {
        $view = View::create();
        $agent = new \App\Entity\Agent();
        $form = $this->createForm(AgentType::class, $agent);
        $form->submit($request->request->all());
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $agent->setCreated(new \DateTime());
            $em->persist($agent);
            $em->flush();
            $view->setData($agent);
            $view->getContext()->setGroups(array(
                'agent'
            ));
            $view->setHeader('Access-Control-Allow-Origin', '*');
            return $handler->handle($view);
        } else {
            return $form;
        }
    }
}
