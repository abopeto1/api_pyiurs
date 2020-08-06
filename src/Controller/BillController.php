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
use App\Form\BillType;
use App\Entity\Bill;
use App\Service\GetPdfService;

class BillController extends Controller
{
  /**
   * List all bills
   * @Rest\Get("/bills")
   * @Rest\QueryParam(name="today",description="Toutes les ventes du jour actuel",default="")
   * @Rest\QueryParam(name="month",description="Toutes les ventes du mois",default="")
   * @Rest\QueryParam(name="billNumber",description="Numero de la Facture",default="")
   * @Rest\QueryParam(name="year",description="Toutes les ventes de l'année",default="")
   * @Rest\QueryParam(name="loan",description="Toutes les ventes à payer ",default="")
   * @Rest\QueryParam(name="reports",description="when user want reports",default=false)
   * @Rest\QueryParam(name="start",description="beginInteval",default="")
   * @Rest\QueryParam(name="end",description="endInterval",default="")
   *
   * @return Response
   */
  public function getBillsAction(ParamFetcher $paramFetcher)
  {
    $month = $paramFetcher->get('month'); $billNumber = $paramFetcher->get('billNumber');
    $bills = null; $year = $paramFetcher->get('year'); $today = $paramFetcher->get('today'); $loan = $paramFetcher->get('loan');
    $reports = $paramFetcher->get('reports'); $start = $paramFetcher->get('start'); $end = $paramFetcher->get('end');

    if($year !== "") {
      $bills = $this->getDoctrine()->getRepository(Bill::class)->findByYear($year);
      $view = View::create();
      $handler = $this->get('fos_rest.view_handler');
      $view->setData($bills);
      $view->getContext()->setGroups(array(
        'list_bills'
      ));
      $view->setHeader('Access-Control-Allow-Origin','*');
      return $handler->handle($view);
    } else if($loan == true){ // Get all loan bills if loan is true
      $bills = $this->getDoctrine()->getRepository(Bill::class)->findBillsCredit();
    } else if($today == true){
      $bills = $this->getDoctrine()->getRepository(Bill::class)->findByCreatedDay(new \DateTime());
    } else if($billNumber != "") {
      $bills = $this->getDoctrine()->getRepository(Bill::class)->findOneBy(array("numero" => $billNumber));
    } else if($month !== ""){
      $bills = $this->getDoctrine()->getRepository(Bill::class)->findByMonth($month);
    } else if($reports == true) {
      $bills = $this->getDoctrine()->getRepository(Bill::class)->findByInterval($start,$end);
    } else {
      $bills = $this->getDoctrine()->getRepository(Bill::class)->findByAll();
    }
    $view = View::create();
    $handler = $this->get('fos_rest.view_handler');
    $view->setData($bills);
    $view->getContext()->setGroups(array(
      'list_bills'
    ));
    $view->setHeader('Access-Control-Allow-Origin','*');
    return $handler->handle($view);
  }

  /**
   * List one bill pdf
   * @Rest\Get("/pdf/bill/{id}")
   */
  public function getBillPdfAction(Request $request, GetPdfService $getpdf)
  {
    $pdf = $getpdf->createPdf('bill',$request->get('id'));
    if(!$pdf){
      return new Response("No bill found for this id",Response::HTTP_NOT_FOUND);
    }

    return $pdf;
  }

  /**
   * List one bill
   * @Rest\Get("/bill/{bill_id}")
   *
   * @return Response
   */
  public function getBillAction(Request $request)
  {
    $bill = $this->getDoctrine()->getRepository(Bill::class)->find($request->get('bill_id'));
    $view = View::create();
    $view->setData($bill);
    $handler = $this->get('fos_rest.view_handler');
    $view->getContext()->setGroups(array(
      'one_bill'
    ));
    $view->setHeader('Access-Control-Allow-Origin','*');
    return $handler->handle($view);
  }

  /**
   * create a bill
   * @Rest\Post("/bill")
   *
   * @return Response
   */
  public function postBillAction(Request $request)
  {
    $em = $this->getDoctrine()->getManager();

    $view = View::create();
    $handler = $this->get('fos_rest.view_handler');
    $repo = $this->getDoctrine()->getRepository(Bill::class);
    $bill = new Bill();
    $form = $this->createForm(BillType::class, $bill);
    $form->submit($request->request->all());
    $numero = $this->setNumberBill($bill->getTypePaiement()->getId());

    if($bill->getTypePaiement()->getId() === 4) {
      $refBill = $request->get('bill_reference');
      $bill->setBillReference($repo->find($refBill['id']));
      $arrayChange = [];
      foreach ($refBill['bill_details'] as $key) {
        if($key['rs'] == true){
          $arrayChange[] = $key['id'];
        }
      }
      $bill->setNumero($numero);
      $billRef = $bill->getBillReference(); $totalReturn = 0;
      
      foreach ($billRef->getBillDetails() as $key) {
        if(in_array($key->getId(),$arrayChange)) {
          $totalReturn = $totalReturn + $key->getNet();
          $pStock = $key->getProduct()->getStock();
          $pStock->setOutQte($pStock->getOutQte() - $key->getQte());
          if($pStock->getQte() - $pStock->getOutQte() > 0){
            $pStock->setAvailable(true);
            $em->persist($pStock);
            $em->persist($key);
          }
        }
      }
      $em->persist($billRef);
      if($totalReturn === 0){
        return new Response("Payement cannot be larger than 0",Response::HTTP_BAD_REQUEST);
      } else {
        $net = $bill->getNet() - $totalReturn;
        if($net <= -2){
          return new Response("Rest cannot be negative",Response::HTTP_BAD_REQUEST);
        } else {
          $bill->setNet($net);

          foreach ($bill->getBillDetails() as $billDetails) {
            $billDetails->setSid($numero)->setBill($bill);
            $billDetails->setCreated(new \DateTime("Africa/Kinshasa"));
            // Set Bill Detail Point
            if($billDetails->getProduct()->getPv() - $billDetails->getNet() <= 2) {
              $point = $billDetails->getNet()/10;
              $billDetails->setPoint($point);
              $customer = $bill->getCustomer();
              $customer->setPoints($bill->getCustomer()->getPoints()+$point);
            } else {
              $point = 0;
              $billDetails->setPoint($point);
              $customer = $bill->getCustomer();
            }
            $product = $billDetails->getProduct();
            $stock = $product->getStock();
            if($stock->getQte() - $stock->getOutQte() < 1){
              return new Response("Product No Available for codebarre : ". $product->getCodebarre());
            } else {
              $stock->setOutQte($stock->getOutQte() + $billDetails->getQte());
              $em->persist($stock);
              if($stock->getQte() - $stock->getOutQte() < 1){
                $stock->setAvailable(false);
              }

              $em->persist($billDetails);
            }
          }
        }
      }
      $bill->setCreated(new \DateTime("Africa/Kinshasa"));
      $em->persist($bill);
      $em->flush();
      $view->setData($bill);
      $view->getContext()->setGroups(array(
        'one_bill'
      ));
      $view->setHeader('Access-Control-Allow-Origin','*');
      return $handler->handle($view);
    }

    $stock = null;
    if($form->isValid()){
      $bill->setNumero($numero)->setCreated(new \DateTime());

      if($bill->getTypePaiement()->getId() === 3){
        $billRef = $bill->getBillReference();
        $reste =  $billRef->getReste() - $bill->getNet();
        if($bill->getNet() > $billRef->getReste()){
          return new Response("Payement cannot be larger than loan",Response::HTTP_BAD_REQUEST);
        } else if($reste < 0){
          return new Response("Rest cannot be negative",Response::HTTP_BAD_REQUEST);
        } else {
          $billRef->setReste($reste);
          $em->persist($billRef);
        }
      } else {
        foreach ($bill->getBillDetails() as $billDetails) {
          $billDetails->setCreated(new \DateTime())->setSid($numero)->setBill($bill);

          // Set Bill Detail Point
          if($billDetails->getProduct()->getPv() - $billDetails->getNet() <= 2) {
            $point = $billDetails->getNet()/10;
            $billDetails->setPoint($point);
            $customer = $bill->getCustomer();
            $customer->setPoints($bill->getCustomer()->getPoints()+$point);
          } else {
            $point = 0;
            $billDetails->setPoint($point);
            $customer = $bill->getCustomer();
          }
          $product = $billDetails->getProduct();
          $stock = $product->getStock();
          if($stock->getQte() - $stock->getOutQte() < 1){
            return new Response("Product No Available for codebarre : ". $product->getCodebarre());
          } else {
            $stock->setOutQte($stock->getOutQte() + $billDetails->getQte());
            $em->persist($stock);
            if($stock->getQte() - $stock->getOutQte() < 1){
              $stock->setAvailable(false);
            }

            $em->persist($billDetails);
          }
        }
      }

      $em->persist($bill);
      $em->flush();
      $view->setData($bill);
      $view->getContext()->setGroups(array(
        'one_bill'
      ));
      $view->setHeader('Access-Control-Allow-Origin','*');
      return $handler->handle($view);
    } else {
      return $form;
    }
  }

  private function setNumberBill($typePaiement){
    if($typePaiement === 1 || $typePaiement === 2){
      $last = $this->getDoctrine()->getRepository(Bill::class)->getLastNumber('FCT00');
      $next = $last['last_id'] + 1;
      return 'FCT000'.$next;
    } else if($typePaiement === 3){
      $last = $this->getDoctrine()->getRepository(Bill::class)->getLastNumber('CRD00');
      $next = $last['last_id'] + 1;
      return 'CRD000'.$next;
    } else if($typePaiement === 4){
      $last = $this->getDoctrine()->getRepository(Bill::class)->getLastNumber('EXC00');
      $next = $last['last_id'] + 1;
      return 'EXC000'.$next;
    }
  }
}
