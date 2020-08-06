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
use App\Entity\Debit;
use App\Form\DebitType;

class DebitController extends AbstractController
{
    /**
   * List debit
   * @Rest\Get("/debits")
   * @Rest\QueryParam(name="date",description="get all entries with the date given",default="")
   *
   * @return Response
   */
    public function getDebitsAction(ParamFetcher $paramFetcher, ViewHandlerInterface $handler)
    {
      $month = $paramFetcher->get('date'); $debits = null;
      if($month != ""){
        $debits = $this->getDoctrine()->getRepository(Debit::class)->findByMonth($month);
      } else {
        $debits = $this->getDoctrine()->getRepository(Debit::class)->findAll();
      }
      $view = View::create();
      $view->setData($debits);
      $view->setHeader('Access-Control-Allow-Origin','*');
      $view->getContext()->setGroups(array(
        'debit'
      ));
      return $handler->handle($view);
    }

    /**
     * Create new Debit
     * @Rest\Post("/debit")
     *
     * @return Response
     */
    public function createCreditAction(Request $request, ViewHandlerInterface $handler)
    {
      $view = View::create();
      $debit = new Debit();
      $form = $this->createForm(DebitType::class, $debit);
      $form->submit($request->request->all());
      if($form->isValid()){
        $em = $this->getDoctrine()->getManager();
        $debit->setCreated(new \DateTime());
        if(count($debit->getDebitEcheances()) > 0){
          foreach ($debit->getDebitEcheances() as $echeance) {
            $echeance->setStatut(false);
            $echeance->setDebit($debit);
            $em->persist($echeance);
          }
        }
        $em->persist($debit);
        $em->flush();
        $view->setData($debit);
        $view->getContext()->setGroups(array(
          'debit'
        ));
        $view->setHeader('Access-Control-Allow-Origin','*');
        return $handler->handle($view);
      } else {
        return $form;
      }
    }
}
