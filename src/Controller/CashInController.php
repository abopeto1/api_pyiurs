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
use App\Entity\CashIn;
use App\Form\CashInType;

class CashInController extends AbstractController
{
  /**
   * List all cashin
   * @Rest\Get("/cashins")
   * @Rest\QueryParam(name="day",description="Get all datas from the date day")
   * @Rest\QueryParam(name="month",description="Get all datas from the date given on the url",default="")
   * @Rest\QueryParam(name="reports",description="when user want reports",default=false)
   * @Rest\QueryParam(name="start",description="beginInteval",default="")
   * @Rest\QueryParam(name="end",description="endInterval",default="")
   *
   * @return Response
   */
  public function getCashInAction(ParamFetcher $paramFetcher, ViewHandlerInterface $handler)
  {
    $month = $paramFetcher->get('month'); $cash = null;
    $day = $paramFetcher->get('day');
    $reports = $paramFetcher->get('reports'); $start = $paramFetcher->get('start'); 
    $end = $paramFetcher->get('end');
    
    if(!empty($day)){
      $cash = $this->getDoctrine()->getRepository(CashIn::class)->findWithDay($day);
    } else if($month != ""){
      $cash = $this->getDoctrine()->getRepository(CashIn::class)->findByMonth($month);
    } else if($reports == true) {
      $cash = $this->getDoctrine()->getRepository(CashIn::class)->findByInterval($start,$end);
    } else {
      $cash = $this->getDoctrine()->getRepository(CashIn::class)->findAll();
    }
    $view = View::create();
    $view->setData($cash);
    $view->getContext()->setGroups(array(
      'cash_in'
    ));
    $view->setHeader('Access-Control-Allow-Origin','*');
    return $handler->handle($view);
  }

  /**
   * Create new cashin
   * @Rest\Post("/cashin")
   *
   * @return Response
   */
  public function createCashinction(Request $request, ViewHandlerInterface $handler)
  {
    $view = View::create();
    $cash = new CashIn();
    $form = $this->createForm(CashInType::class, $cash);
    $form->submit($request->request->all());
    if($form->isValid()){
      $em = $this->getDoctrine()->getManager();
      $cash->setCreated(new \DateTime());
      $em->persist($cash);
      $em->flush();
      $view->setData($cash);
      $view->getContext()->setGroups(array(
        'cash_in'
      ));
      $view->setHeader('Access-Control-Allow-Origin','*');
      return $handler->handle($view);
    } else {
      return $form;
    }
  }

}
