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
// use App\Form\SoldOutType;
use App\Entity\SoldType;

class SoldTypeController extends Controller
{
  /**
   * List all expence compte
   * @Rest\Get("/promotion_types", name="sold_type")
   *
   * @return Response
   */
    public function getAllPromotionType()
    {
      $types = $this->getDoctrine()->getRepository(SoldType::class)->findAll();
      $view = View::create();
      $handler = $this->get('fos_rest.view_handler');
      $view->setData($types);
      $view->setHeader('Access-Control-Allow-Origin','*');
      $view->getContext()->setGroups(array(
        "solde_type"
      ));
      return $handler->handle($view);
    }
}
