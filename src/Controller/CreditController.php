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
use App\Entity\Credit;
use App\Form\CreditType;

class CreditController extends AbstractController
{
  /**
   * List credit
   * @Rest\Get("/credits")
   * @Rest\QueryParam(name="date",description="get all entries with the date given",default="")
   *
   * @return Response
   */
    public function getCreditsAction(ParamFetcher $paramFetcher, ViewHandlerInterface $handler)
    {
      $month = $paramFetcher->get('date'); $credits = null;
      if($month != ""){
        $credits = $this->getDoctrine()->getRepository(Credit::class)->findByMonth(new \DateTime());
      } else {
        $credits = $this->getDoctrine()->getRepository(Credit::class)->findAll();
      }
      $view = View::create();
      $view->setData($credits);
      $view->setHeader('Access-Control-Allow-Origin','*');
      $view->getContext()->setGroups(array(
        'credit'
      ));
      return $handler->handle($view);
    }

      /**
       * Create new Credit
       * @Rest\Post("/credit")
       *
       * @return Response
       */
      public function createCreditAction(Request $request, ViewHandlerInterface $handler)
      {
        $view = View::create();
        $credit = new Credit();
        $form = $this->createForm(CreditType::class, $credit);
        $form->submit($request->request->all());
        if($form->isValid()){
          $em = $this->getDoctrine()->getManager();
          $credit->setCreated(new \DateTime());
          if($credit->getType() == "divers"){
            $credit->setCodeCredit($this->numAlea(5,'divers'));
          } else if ($credit->getType() == "bank") {
            $credit->setCodeCredit($this->numAlea(5, 'bank'));
          } else {
            $credit->setCodeCredit($this->numAlea(5,'dette'));
          }
          if(count($credit->getCreditEcheances()) > 0){
            foreach ($credit->getCreditEcheances() as $echeance) {
              $echeance->setStatut(false);
          $echeance->setCredit($credit);
              $em->persist($echeance);
            }
          }
          $em->persist($credit);
          $em->flush();
          $view->setData($credit);
          $view->getContext()->setGroups(array(
            'credit'
          ));
          $view->setHeader('Access-Control-Allow-Origin','*');
          return $handler->handle($view);
        } else {
          return $form;
        }
      }

      public function numAlea($Length, $Prifx) {

        $chara = array(0 => array(0, 2, 3, 4, 5, 6, 7, 8, 9),
            1 => array("A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z"));

        $genr = array();

        for ($i = 0; $i < $Length; $i++) {
            // On choisit au hasard entre quelle sorte de caractères choisir
            $p = rand(0, 1);
            switch ($p) {
                case 0: $q = rand(0, 8);
                    break;
                case 1: $q = rand(0, 24);
                    break;
            }

            $genr[$i] = $chara[$p][$q];
        }

        return $Prifx . implode("", $genr);
    }
}
