<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Annotations\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use FOS\RestBundle\View\ViewHandlerInterface;
use App\Entity\ExpenceCompte;
use App\Form\ExpenceCompteType;

class ExpenceCompteController extends AbstractController
{
  /**
   * List all expence compte
   * @Rest\Get("/expence_comptes")
   *
   * @return Response
   */
    public function index(ViewHandlerInterface $handler)
    {
      $comptes = $this->getDoctrine()->getRepository(ExpenceCompte::class)->findAll();
      $view = View::create();
      $view->setData($comptes);
      $view->setHeader('Access-Control-Allow-Origin','*');
      $view->getContext()->setGroups(array(
        'expence_compte'
      ));
      return $handler->handle($view);
    }

  /**
   * Create a expence compte
   * @Rest\Post("/expence_compte")
   * 
   * @return Response
   */
    public function create(Request $request, ViewHandlerInterface $handler){
      $em = $this->getDoctrine()->getManager();
      $expenceCompte =  new ExpenceCompte();
      $form = $this->createForm(ExpenceCompteType::class, $expenceCompte);
      $form->submit($request->request->all());
      if ($form->isValid()) {
        $em->persist($expenceCompte);
        $em->flush();
        $view = View::create();
        $view->setData($expenceCompte);
        $view->getContext()->setGroups(array(
          'expence_compte'
        ));
        $view->setHeader('Access-Control-Allow-Origin', '*');
        return $handler->handle($view);
      } else {
        return $form;
      }
    }
}
