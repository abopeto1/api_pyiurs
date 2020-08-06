<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Annotations\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\View\View;

class SegmentController extends Controller
{
  /**
   * List all bills
   * @Rest\Get("/segments")
   * @Rest\QueryParam(name="reports",description="Get on a period")
   * @Rest\QueryParam(name="start",description="the begin date")
   * @Rest\QueryParam(name="end",description="the end data")
   * @Rest\QueryParam(name="year",description="Toutes les ventes du jour actuel")
   *
   * @return Response
   */
  public function getBillsAction(ParamFetcher $paramFetcher)
  {
    $year = $paramFetcher->get('year');
    $reports = $paramFetcher->get('reports');
    $start = $paramFetcher->get('start');
    $end = $paramFetcher->get('end');
    $segments = null;

    if ($year !== "") {
        $segments = $this->getDoctrine()->getRepository('App:Segment')->findProductsSellsYear($year);
    } else if ($reports) {
        $segments = $this->getDoctrine()->getRepository('App:Segment')->findProductsSellsReports($start,$end);
    }

    $view = View::create();
    $handler = $this->get('fos_rest.view_handler');
    $view->setData($segments);
    $view->getContext()->setGroups(array(
    'segments'
    ));
    $view->setHeader('Access-Control-Allow-Origin','*');
    return $handler->handle($view);
  }
}
