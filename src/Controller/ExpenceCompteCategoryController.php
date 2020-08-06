<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Annotations\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use App\Entity\ExpenceCompteCategorie;

class ExpenceCompteCategoryController extends Controller
{
  /**
   * List all expence compte
   * @Rest\Get("/expence_compte_categories")
   * @Rest\QueryParam(name="month",description="Toutes les ventes du mois",default="")
   *
   * @return Response
   */
    public function index(ParamFetcher $paramFetcher)
    {
      $expence_compte_categories = null; $month = $paramFetcher->get('month');
      if(!empty($month)){
        $expence_compte_categories = $this->getDoctrine()->getRepository(ExpenceCompteCategorie::class)->findByMonth($month);
      } else {
        $expence_compte_categories = $this->getDoctrine()->getRepository(ExpenceCompteCategorie::class)->findAll();
      }
      $view = View::create();
      $handler = $this->get('fos_rest.view_handler');
      $view->setData($expence_compte_categories);
      $view->setHeader('Access-Control-Allow-Origin','*');
      $view->getContext()->setGroups(array(
        'expenceCompteCategory'
      ));
      return $handler->handle($view);
    }

  /**
   * create an expence compte category
   * @Rest\Post("/expence_compte_category")
   * @ParamConverter("category", converter="fos_rest.request_body")
   *
   * @return Response
   */
  public function create(ExpenceCompteCategorie $category)
  {
    $em = $this->getDoctrine()->getManager();
    $em->persist($category);
    $em->flush();
    $view = View::create();
    $handler = $this->get('fos_rest.view_handler');
    $view->setData($category);
    $view->setHeader('Access-Control-Allow-Origin', '*');
    $view->getContext()->setGroups(array(
      'expenceCompteCategory'
    ));
    return $handler->handle($view);
  }
}
