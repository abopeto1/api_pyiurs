<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\ViewHandlerInterface;
use FOS\RestBundle\View\View;
use App\Form\AgentLoanType;
use App\Entity\AgentLoan;

class AgentLoanController extends AbstractController
{
    /**
     * create an agent
     * @Rest\Post("/agent_loan")
     *
     * @return Response
     */
    public function createCustomerAction(Request $request, ViewHandlerInterface $handler)
    {
        $view = View::create();
        $loan = new AgentLoan();
        $form = $this->createForm(AgentLoanType::class, $loan);
        $form->submit($request->request->all());
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $loan->setCreated(new \DateTime());
            $em->persist($loan);
            $em->flush();
            $view->setData($loan);
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
