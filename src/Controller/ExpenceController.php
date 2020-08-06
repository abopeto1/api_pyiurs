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
use App\Service\GetPdfService;
use App\Form\ExpenceType;
use App\Form\ExpenceUpdateFormType;
use App\Entity\Expence;

class ExpenceController extends AbstractController
{
  /**
   * List month expences
   * @Rest\Get("/expences")
   * @Rest\QueryParam(name="expence_compte",description="Mois",default="")
   * @Rest\QueryParam(name="month",description="Mois",default="")
   * @Rest\QueryParam(name="reports",description="reports",default=false)
   * @Rest\QueryParam(name="start",description="start",default="")
   * @Rest\QueryParam(name="end",description="end",default="")
   *
   * @return Response
   */
    public function getExpencesAction(ParamFetcher $paramFetcher, ViewHandlerInterface $handler)
    {
      $month = $paramFetcher->get('month'); $reports = $paramFetcher->get('reports'); $expence_compte = $paramFetcher->get('expence_compte');
      if($reports == true){
        $start = $paramFetcher->get('start');$end = $paramFetcher->get('end');
        $expences = $this->getDoctrine()->getRepository(Expence::class)->findByIntervalCreated($start,$end);
        $view = View::create();
        $view->setData($expences);
        $view->setHeader('Access-Control-Allow-Origin','*');
        $view->getContext()->setGroups(array(
          'expence'
        ));
        return $handler->handle($view);
      } else if($month != "" && $expence_compte != ""){
        $expences = $this->getDoctrine()->getRepository(Expence::class)->findByMonthAndExpenceCompte($month,$expence_compte);
        $view = View::create();
        $view->setData($expences);
        $view->setHeader('Access-Control-Allow-Origin','*');
        $view->getContext()->setGroups(array(
          'expence'
        ));
        return $handler->handle($view);
      } else if($month != ""){
        $expences = $this->getDoctrine()->getRepository(Expence::class)->findByMonth($month);
        $view = View::create();
        $view->setData($expences);
        $view->setHeader('Access-Control-Allow-Origin','*');
        $view->getContext()->setGroups(array(
          'expence'
        ));
        return $handler->handle($view);
      }
    }

    /**
     * create a new expence
     * @Rest\Post("/expence")
     *
     * @return Response
     */
    public function createExpenceAction(Request $request, ViewHandlerInterface $handler)
    {
      $view = View::create();
      $expence = new Expence();
      $form = $this->createForm(ExpenceType::class, $expence);
      $form->submit($request->request->all());
      $d = new \DateTime();
      $code = 'TRANS_'.$d->format('Ymd_H:i');
      if($form->isValid()){
        $em = $this->getDoctrine()->getManager();
        $expence
          ->setStatut(false)
          ->setCode($code)
          ->setCreated(new \DateTime());
        $em->persist($expence);
        $em->flush();
        $view->setData($expence);
        $view->getContext()->setGroups(array(
          'expence'
        ));
        return $handler->handle($view);
      } else {
        return $form;
      }
    }

  /**
   * update an expence
   * @Rest\Put("/expence/{id}")
   *
   * @return Response
   */
  public function updateExpenceAction(Request $request, ViewHandlerInterface $handler)
  {
    $view = View::create();
    $expence = $this->getDoctrine()->getRepository(Expence::class)->find($request->get('id'));
    $form = $this->createForm(ExpenceUpdateFormType::class, $expence);
    $form->submit($request->request->all());
    $date = new \DateTime();
    if ($form->isValid()) {
      $em = $this->getDoctrine()->getManager();
      $expence->setValidated(new \DateTime());
      $em->merge($expence);
      $em->flush();
      $view->setData($expence);
      $view->getContext()->setGroups(array(
        'expence'
      ));
      return $handler->handle($view);
    } else {
      return $form;
    }
  }

  /**
   * update an expence
   * @Rest\Delete("/expence/{id}")
   *
   * @return Response
   */
  public function deleteExpenceAction(Request $request)
  {
    $view = View::create();
    $expence = $this->getDoctrine()->getRepository(Expence::class)->find($request->get('id'));
    $em = $this->getDoctrine()->getManager();
    $em->remove($expence);
    $em->flush();
  }

    /**
     * get expence pdf
     * @Rest\Get("/pdf/expence/{id}")
     */
    public function getExpencePdfAction(Request $request, GetPdfService $getpdf)
    {
      $pdf = $getpdf->createPdf('expence',$request->get('id'));
      if(!$pdf){
        return new Response("No Expence found for this id",Response::HTTP_NOT_FOUND);
      }

      return $pdf;
    }
}
