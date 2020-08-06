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
use App\Entity\TypePaiement;

class TypePaiementController extends Controller
{
  /**
   * List all typePaiement
   * @Rest\Get("/type_paiements")
   * @Rest\QueryParam(name="today",description="Toutes les ventes du jour actuel",default="")
   * @Rest\QueryParam(name="getBills",description="Get All Bills",default="")
   *
   * @return Response
   */
    public function getTypePaiementsAction(ParamFetcher $paramFetcher)
    {
      $typePaiements = null; $today = $paramFetcher->get('today');
      if($today != ""){
        $typePaiements = $this->getDoctrine()->getRepository(TypePaiement::class)->findByDate($today);
      } else {
        $typePaiements = $this->getDoctrine()->getRepository(TypePaiement::class)->findAll();
      }
      $view = View::create();
      $handler = $this->get('fos_rest.view_handler');
      $view->setData($typePaiements);
      $view->getContext()->setGroups(array(
        "type_paiement"
      ));
      $view->setHeader('Access-Control-Allow-Origin','*');
      return $handler->handle($view);
    }
}
