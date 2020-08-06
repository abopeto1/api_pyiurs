<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Annotations\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use App\Entity\Budget;
use App\Form\BudgetType;

class BudgetController extends Controller
{
  /**
   * create a budget
   * @Rest\Post("/budget")
   *
   * @return Response
   */
  public function createBudgetAction(Request $request)
  {
    $view = View::create();
    $handler = $this->get('fos_rest.view_handler');
    $budget = new Budget();
    $form = $this->createForm(BudgetType::class, $budget);
    $form->submit($request->request->all());
    if($form->isValid()){
      $em = $this->getDoctrine()->getManager();
      $em->persist($budget);
      $em->flush();
      $view->setData($budget);
      $view->getContext()->setGroups(array(
        'budget'
      ));
      $view->setHeader('Access-Control-Allow-Origin','*');
      return $handler->handle($view);
    } else {
      return $form;
    }
  }

  /**
   * update a budget
   * @Rest\Put("/budget/{id}")
   *
   * @return Response
   */
  public function updateBudgetAction(Request $request)
  {
    $view = View::create();
    $handler = $this->get('fos_rest.view_handler');
    $em = $this->getDoctrine()->getManager();
    $budget = $this->getDoctrine()->getRepository(Budget::class)->find($request->get('id'));
    $form = $this->createForm(BudgetType::class, $budget);
    $form->submit($request->request->all());
    if($form->isValid()){
      $em->merge($budget);
      $em->flush();
      $view->setData($budget);
      $view->getContext()->setGroups(array(
        'budget'
      ));
      $view->setHeader('Access-Control-Allow-Origin','*');
      return $handler->handle($view);
    } else {
      return $form;
    }
  }
}
