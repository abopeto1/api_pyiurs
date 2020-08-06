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
use App\Form\ClotureDayType;
use App\Entity\ClotureDay;
use App\Entity\Bill;
use App\Entity\Expence;
use App\Entity\Credit;
use App\Entity\CashIn;
use App\Entity\Service;
use App\Service\GetPdfService;

class ClotureDayController extends AbstractController
{
  /**
   * List cloture month history
   * @Rest\Get("/clotures")
   * @Rest\QueryParam(name="month",description="Tous les clotures du mois",default="")
   * @Rest\QueryParam(name="reports",description="when user want reports",default=false)
   * @Rest\QueryParam(name="start",description="beginInteval",default="")
   * @Rest\QueryParam(name="end",description="endInterval",default="")
   *
   * @return Response
   */
    public function getClotureAction(ParamFetcher $paramFetcher, ViewHandlerInterface $handler)
    {
      $month = $paramFetcher->get('month');
      $reports = $paramFetcher->get('reports');
      $start = $paramFetcher->get('start'); $end = $paramFetcher->get('end');

      $clotures = null;
      if($reports){
        $clotures = $this->getDoctrine()->getRepository(ClotureDay::class)->findWithInterval($start,$end);
      } else if($month !== ""){
        $clotures = $this->getDoctrine()->getRepository(ClotureDay::class)->findByMonth($month);
      } else {
        $clotures = $this->getDoctrine()->getRepository(ClotureDay::class)->findAll();
      }
      $view = View::create();
      $view->setData($clotures);
      $view->setHeader('Access-Control-Allow-Origin','*');
      $view->getContext()->setGroups(array(
        "clotures"
      ));
      return $handler->handle($view);
    }

    /**
     * Get Cloture By Id
     * @Rest\Get("/cloture/{id}")
     *
     * @return Response
     */
    public function getOneClotureAction(Request $request, ViewHandlerInterface $handler)
    {
      $cloture = $this->getDoctrine()->getRepository(ClotureDay::class)->find($request->get('id'));
      $view = View::create();
      $view->setData($cloture);
      $view->getContext()->setGroups(array(
        'report_cloture'
      ));
      $view->setHeader('Access-Control-Allow-Origin','*');
      return $handler->handle($view);
    }

    /**
     * close a day
     * @Rest\Post("/cloture")
     *
     * @return Response
     */
    public function postClotureAction(Request $request, ViewHandlerInterface $handler)
    {
      $view = View::create();
      $cloture = new ClotureDay();
      $form = $this->createForm(ClotureDayType::class, $cloture);
      $form->submit($request->request->all());
      if($form->isValid()){
        $em = $this->getDoctrine()->getManager();
        $cloture->setCreated(new \DateTime());
        $em->persist($cloture);

        $bills = $this->getDoctrine()->getRepository(Bill::class)->findByDate($cloture->getCreated());
        foreach ($bills as $bill) {
          $cloture->addBill($bill);
          $bill->setClotureDay($cloture);
          $em->persist($bill);
        }

        $expences = $this->getDoctrine()->getRepository(Expence::class)->findByDate($cloture->getCreated());
        foreach ($expences as $expence) {
          $expence->setClotureDay($cloture);
          $em->persist($expence);
        }
        
        $cashins = $this->getDoctrine()->getRepository(CashIn::class)->findByDate($cloture->getCreated());
        foreach ($cashins as $cashin) {
          $cashin->setClotureDay($cloture);
          $em->persist($cashin);
        }

        $services = $this->getDoctrine()->getRepository(Service::class)->findWithDate($cloture->getCreated());
        foreach ($services as $service) {
          $service->setClotureDay($cloture);
          $em->persist($service);
        }

        $em->persist($cloture);
        $em->flush();

        $this->forward('App\Controller\StockLogController::createSemiLogs',[
          'date' => $cloture->getCreated()->format('Y-m-d'),
        ]);
        
        $view->setData($cloture);
        $view->getContext()->setGroups(array(
          'clotures'
        ));
        $view->setHeader('Access-Control-Allow-Origin','*');
        return $handler->handle($view);
      } else {
        return $form;
      }
    }

    /**
     * remove cloture
     * @Rest\Delete("/cloture/{id}")
     * @Rest\QueryParam(name="month",description="Tous les clotures du mois",default="")
     *
     * @return Response
     */
      public function removeClotureAction(Request $request)
      {
        $em = $this->getDoctrine()->getManager();
        $cloture = $this->getDoctrine()->getRepository(ClotureDay::class)->find($request->get('id'));
        $bills = $cloture->getBills();
        foreach ($bills as $bill) {
          $cloture->removeBill($bill);
        }
        $expences = $cloture->getExpences();
        foreach ($expences as $expence) {
          $cloture->removeExpence($expence);
        }
        $cashins = $cloture->getCashIns();
        foreach ($cashins as $cashin) {
          $cloture->removeCashIn($cashin);
        }
        $em->remove($cloture);
        $em->flush();
      }

    /**
     * List cloture day pdf
     * @Rest\Get("/pdf/cloture/{id}")
     */
    public function getCloturePdfAction(Request $request, GetPdfService $getpdf)
    {
      $pdf = $getpdf->createPdf('clotureDay',$request->get('id'));

      $pdf = $getpdf->createPdf('clotureDay',$request->get('id'));
      if(!$pdf){
        return new Response("No clotureDay found for this id",Response::HTTP_NOT_FOUND);
      }

      return $pdf;
    }

    /**
     * List cloture day by date pdf
     * @Rest\Get("/cloture/date-pdf/{day}")
     */
    public function getClotureByDatePdfAction(Request $request)
    {
      $cloture = $this->getDoctrine()->getRepository(ClotureDay::class)->findByDate($request->get('day'));
      $billsMonth = $this->getDoctrine()->getRepository(Bill::class)->findByMonth($cloture->getCreated());
      $cashin = $this->getDoctrine()->getRepository(CashIn::class)->findByMonth($cloture->getCreated());
      $credits = $this->getDoctrine()->getRepository(Credit::class)->findByMonth($cloture->getCreated());
      $expences = $this->getDoctrine()->getRepository(Expence::class)->findByMonth($cloture->getCreated()->format('Y-m'));
      $snappy = $this->get('knp_snappy.pdf');
      $html = $this->renderView('cloture.html.twig',[
        'cloture' => $cloture, 'billsMonth' => $billsMonth, 'cashin' => $cashin, 'credits' => $credits, 'expences' => $expences,
        ]);
      $footer = $this->renderView('footer.html.twig');
      $snappy->setOption('footer-html',$footer);
      $filename = "Cloture Journalier";
      $response = new Response(
        $snappy->getOutputFromHtml($html,array(
          'page-height' =>  297,'page-width' => 210,'margin-top' => 5,'margin-bottom' => 5,'margin-left' =>10,'margin-right'=>10,
        )),200,array(
          'Content-Type' => 'application/pdf',
          'Content-Disposition' => 'inline; filename="'.$filename.'.pdf"',
        )
      );
      $response->headers->set('Access-Control-Allow-Origin','*');
      return $response;
    }
}
