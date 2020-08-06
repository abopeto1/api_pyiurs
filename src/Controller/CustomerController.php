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
use App\Form\CustomerType;

class CustomerController extends AbstractController
{
  /**
   * List all customers
   * @Rest\Get("/customers")
   * @Rest\QueryParam(name="loan",description="All loans customers ")
   * @Rest\QueryParam(name="reports",description="To get reports")
   * @Rest\QueryParam(name="filter",description="the filter of the reports")
   * @Rest\QueryParam(name="start",description="Date de debut")
   * @Rest\QueryParam(name="end",description="Date de fin")
   *
   * @return Response
   */
  public function getCustomersAction(ViewHandlerInterface $handler, ParamFetcher $paramFetcher)
  {
    $loan = $paramFetcher->get('loan'); $customers = null;
    $reports = $paramFetcher->get('reports');
    $filter = $paramFetcher->get('filter');
    $start = $paramFetcher->get('start');
    $end = $paramFetcher->get('end'); 

    if($loan){
      $customers = $this->getDoctrine()->getRepository("App:Customer")->findWithBillLoan($reports, $start, $end);
    } else if($reports){
      $customers = $this->getDoctrine()->getRepository("App:Customer")->findByFilter($start, $end, $filter);
    } else {
      $customers = $this->getDoctrine()->getRepository("App:Customer")->findAllOrderByName();
      
      foreach($customers as $customer){
        $total_sell = 0; $total_count = count($customer->getBills());
        foreach($customer->getBills() as $bill){
          $total_sell = $bill->getTypePaiement()->getId() == 2 ? $total_sell + $bill->getAccompte() : $total_sell + $bill->getNet();
        }
        $customer->setTotalArticleSell($total_sell)->setTotalArticleCount($total_count);
      }

      $view = View::create();
      $view->setData($customers);
      $view->getContext()->setGroups(array(
        "list_customer"
      ));
      $view->setHeader('Access-Control-Allow-Origin', '*');
      return $handler->handle($view);
    }
    $view = View::create();
    $view->setData($customers);
    $view->getContext()->setGroups(array(
      "customer"
    ));
    $view->setHeader('Access-Control-Allow-Origin','*');
    return $handler->handle($view);
  }

  /**
   * create a customer
   * @Rest\Post("/customer")
   *
   * @return Response
   */
  public function createCustomerAction(Request $request, ViewHandlerInterface $handler)
  {
    $view = View::create();
    $customer = new \App\Entity\Customer();
    $form = $this->createForm(CustomerType::class, $customer);
    $form->submit($request->request->all());
    if($form->isValid()){
      $em = $this->getDoctrine()->getManager();
      $category = $this->getDoctrine()->getRepository("App:CustomerCategorie")->find(1);
      $customer->setCategorie($category);
      $customer->setPoints(0);
      $customer->setCreated(new \DateTime());
      $em->persist($customer);
      $em->flush();
      $view->setData($customer);
      $view->getContext()->setGroups(array(
        'customer'
      ));
      $view->setHeader('Access-Control-Allow-Origin','*');
      return $handler->handle($view);
    } else {
      return $form;
    }
  }
}
