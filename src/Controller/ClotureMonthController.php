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
use App\Form\ClotureMonthType;
use App\Entity\ClotureMonth;
use App\Entity\Segment;
use App\Entity\ExpenceCompteCategorie;
use App\Entity\Customer;
use App\Entity\Bill;
use App\Entity\Expence;
use App\Entity\CashIn;
use App\Service\GetPdfService;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ClotureMonthController extends AbstractController
{
  /**
   * List cloture month history
   * @Rest\Get("/cloture_months")
   *
   * @return Response
   */
    public function getClotureAction(ParamFetcher $paramFetcher, ViewHandlerInterface $handler)
    {
      $clotures = $this->getDoctrine()->getRepository(ClotureMonth::class)->findAll();
      $view = View::create();
      $view->setData($clotures);
      $view->setHeader('Access-Control-Allow-Origin','*');
      $view->getContext()->setGroups(array(
        "cloture_month"
      ));
      return $handler->handle($view);
    }

  /**
   * List cloture day pdf
   * @Rest\Get("/pdf/cloture_month/{id}")
   */
  public function getCloturePdfAction(Request $request, GetPdfService $getpdf)
  {
    // $pdf = $getpdf->createPdf('clotureMonth', $request->get('id'));

    // if (!$pdf) {
    //   return new Response("No clotureMonth found for this id", Response::HTTP_NOT_FOUND);
    // }

    // return $pdf;
    $entity = $this->getDoctrine()->getRepository(ClotureMonth::class)->find($request->get('id'));
    if(!$entity){
      return new Response("No clotureMonth found for this id", Response::HTTP_NOT_FOUND);
    }
    
    $pdf = \dirname(__DIR__) . '/../public/cloture_month_reports/' . $entity->getYear() . "-" . $entity->getMonth() . ".pdf";
    $response = new BinaryFileResponse(
      $pdf,
      // 200,
      // array(
      //   'Content-Type' => 'application/pdf',
      //   'Content-Disposition' => "inline; filename='Cloture Mois" . $entity->getCreated()->format('Y-m') . ".pdf'",
      // )
    );
    $response->headers->set('Access-Control-Allow-Origin', '*');
    return $response;
  }

  /**
   * @Rest\Post("/cloture_month")
   *
   * @return Response
   */
  public function postClotureAction(Request $request, GetPdfService $createPdf, ViewHandlerInterface $handler)
  {
    $view = View::create();
    $cloture = new ClotureMonth();
    $form = $this->createForm(ClotureMonthType::class, $cloture);
    $form->submit($request->request->all());
    if($form->isValid()){
      $em = $this->getDoctrine()->getManager();
      $cloture->setCreated(new \DateTime());
      $em->persist($cloture);
      $bills = $this->getDoctrine()->getRepository(Bill::class)->findByMonth($cloture->getYear()."-".$cloture->getMonth()."-01");
      foreach ($bills as $bill) {
        $cloture->addBill($bill);
      }
      $expences = $this->getDoctrine()->getRepository(Expence::class)->findByMonth($cloture->getYear()."-".$cloture->getMonth()."-01");
      foreach ($expences as $expence) {
        $cloture->addExpence($expence);
      }
      $cashins = $this->getDoctrine()->getRepository(CashIn::class)->findByMonth($cloture->getYear()."-".$cloture->getMonth()."-01");
      foreach ($cashins as $cashin) {
        $cloture->addCashIn($cashin);
      }
      $em->flush();
      $view->setData($cloture);
      $view->getContext()->setGroups(array(
        'cloture_month'
      ));
      $view->setHeader('Access-Control-Allow-Origin','*');
      $pdf = $createPdf->createPdf('clotureMonth', $cloture->getId());
      return $handler->handle($view);
    } else {
      return $form;
    }
  }
}
