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
use App\Form\SoldOutType;
use App\Entity\SoldOut;
use App\Entity\Product;

class SoldOutController extends Controller
{
  /**
   * List all expence compte
   * @Rest\Get("/promotions", name="sold_out")
   *
   * @return Response
   */
    public function getAll()
    {
      $sold = $this->getDoctrine()->getRepository(SoldOut::class)->findAll();
      $view = View::create();
      $handler = $this->get('fos_rest.view_handler');
      $view->setData($sold);
      $view->setHeader('Access-Control-Allow-Origin','*');
      $view->getContext()->setGroups(array(
        'solde'
      ));
      return $handler->handle($view);
    }

    /**
     * Get one promotion details/
     * @Rest\Get("/promotion/{id}",requirements={"id"="\d+"})
     *
     * @return Response
     */
      public function getOne(Request $request)
      {
        $sold = $this->getDoctrine()->getRepository(SoldOut::class)->find($request->get('id'));
        $view = View::create();
        $handler = $this->get('fos_rest.view_handler');
        $view->setData($sold);
        $view->setHeader('Access-Control-Allow-Origin','*');
        $view->getContext()->setGroups(array(
          'one_sold'
        ));
        return $handler->handle($view);
      }

    /**
     * create a promotion
     * @Rest\Post("/promotion")
     *
     * @return Response
     */
    public function postBillAction(Request $request)
    {
      $em = $this->getDoctrine()->getManager();
      $view = View::create();
      $handler = $this->get('fos_rest.view_handler');
      $soldout = new SoldOut();
      $form = $this->createForm(SoldOutType::class, $soldout);
      $form->submit($request->request->all());
      if($form->isValid()){
        $em = $this->getDoctrine()->getManager();
        $soldout->setCreated(new \DateTime());
        $em->persist($soldout);
        $em->flush();
        $view->setData($soldout);
        $view->getContext()->setGroups(array(
          'solde'
        ));
        $view->setHeader('Access-Control-Allow-Origin','*');
        return $handler->handle($view);
      } else {
        return $form;
      }
    }

    /**
     * update a promotion
     * @Rest\Put("/promotion/{id}")
     *
     * @return Response
     */
    public function putPromotionAction(Request $request)
    {
      $em = $this->getDoctrine()->getManager();
      $sold = $this->getDoctrine()->getRepository(SoldOut::class)->find($request->get('id'));
      if(empty($sold)){
        return new Response("Promotion doesn't exist",Response::HTTP_NOT_FOUND);
      }
      $ids = $request->get('products');

      $view = View::create();
      $handler = $this->get('fos_rest.view_handler');

      if(!empty($ids) && count($ids) > 0){
        foreach ($ids as $id) {
          $product = $this->getDoctrine()->getRepository(Product::class)->find($id);
          $sold->addProduct($product);
          $em->persist($product);
        }
        $sold->setCreated(new \DateTime());
        $em->persist($sold);
        $em->flush();
        $view->setData($sold);
        $view->getContext()->setGroups(array(
          'solde'
        ));
        $view->setHeader('Access-Control-Allow-Origin','*');
        return $handler->handle($view);
      } else {
        $form = $this->createForm(SoldOutType::class, $sold);
        $form->submit($request->request->all());
        if($form->isValid()){
          $em->merge($sold);
          $em->flush();
          $view->setData($sold);
          $view->getContext()->setGroups(array(
            'solde'
          ));
          $view->setHeader('Access-Control-Allow-Origin','*');
          return $handler->handle($view);
        } else {
          return $form;
        }
      }
      
      return new Response("No Product Found",Response::HTTP_NOT_FOUND);
    }

    /**
     * remove a promotion
     * @Rest\Delete("/promotion/{id}")
     *
     * @return Response
     */
    public function deletePromotionAction(Request $request)
    {
      $em = $this->getDoctrine()->getManager();
      $sold = $this->getDoctrine()->getRepository(SoldOut::class)->find($request->get('id'));
      $em->remove($sold);
      $em->flush();
      return new Response("Promotion Deleted With Success",Response::HTTP_NO_CONTENT);
    }
}
