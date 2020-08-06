<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Annotations\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\View\View;
use FOS\RestBundle\View\ViewHandlerInterface;
use App\Entity\BillDetails;


class BillDetailsController extends AbstractController
{
    /**
     * Get All bills details on interval
     * @Rest\Get("/bill_details")
     * @Rest\QueryParam(name="reports",description="when user want reports",default=false)
     * @Rest\QueryParam(name="start",description="beginInteval",default="")
     * @Rest\QueryParam(name="end",description="endInterval",default="")
     *
     * @return Response
     */
    public function getBillsDetailsIntervalDateAction(ParamFetcher $paramFetcher, ViewHandlerInterface $handler)
    {
        $reports = $paramFetcher->get('reports');
        $start = $paramFetcher->get('start'); $end = $paramFetcher->get('end');
        $billDetails = $this->getDoctrine()->getRepository(BillDetails::class)->findByInterval($start,$end);
        $view = View::create();
        $view->setData($billDetails);
        $view->getContext()->setGroups(array(
          'bill_detail'
        ));
        $view->setHeader('Access-Control-Allow-Origin','*');
        return $handler->handle($view);
    }
}
