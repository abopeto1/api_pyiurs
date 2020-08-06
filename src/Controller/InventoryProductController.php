<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\View\View;
use FOS\RestBundle\View\ViewHandlerInterface;
use App\Entity\InventoryProduct;
use App\Form\InventoryProductType;

class InventoryProductController extends AbstractController
{
  /**
   * get inventory_products
   * @Rest\Get("/inventory_products")
   * @Rest\QueryParam(name="codebarre")
   * @Rest\QueryParam(name="types")
   * @Rest\QueryParam(name="inventory")
   * 
   */
  public function index(ParamFetcher $paramFetcher, ViewHandlerInterface $handler){
    $codebarre = $paramFetcher->get('codebarre'); $types = $paramFetcher->get('types');
    $inventory = $paramFetcher->get('inventory');
    $inventory_products = null;
    if($codebarre && $types && $inventory){
      $inventory_products = $this->getDoctrine()->getRepository(InventoryProduct::class)
                                ->findWithCodebarreAndType($codebarre, $inventory, $types);
    } else {
      $inventory_products = $this->getDoctrine()->getRepository(InventoryProduct::class)->findAll();
    }
    $view = View::create();
    $view->setData($inventory_products);
    $view->getContext()->setGroups(array(
      'inventory'
    ));
    $view->setHeader('Access-Control-Allow-Origin', '*');
    return $handler->handle($view);
  }
  /**
   * update a inventory_product
   * @Rest\Put("/inventory_product/{id}")
   *
   * @return Response
   */
  public function updateInventoryProductAction(Request $request, ViewHandlerInterface $handler)
  {
    $view = View::create();
    $em = $this->getDoctrine()->getManager();
    $inventoryProduct = $this->getDoctrine()->getRepository(InventoryProduct::class)->find($request->get('id'));
    $form = $this->createForm(InventoryProductType::class, $inventoryProduct);
    $form->submit($request->request->all());
    if($form->isValid()){
      $em->merge($inventoryProduct);
      $em->flush();
      $view->setData($inventoryProduct);
      $view->getContext()->setGroups(array(
        'inventory'
      ));
      $view->setHeader('Access-Control-Allow-Origin','*');
      return $handler->handle($view);
    } else {
      return $form;
    }
  }

  /**
   * update inventory_product
   * @Rest\Put("/inventory/{invenoty_id}/inventory_product/{id}")
   *
   * @return Response
   */
  public function updateInventoryProductStatusAction(Request $request, ViewHandlerInterface $handler)
  {
    $view = View::create();
    $em = $this->getDoctrine()->getManager();
    $inventoryProduct = $this->getDoctrine()->getRepository(InventoryProduct::class)->find($request->get('id'));
    $inventoryProduct->setStatus(true)->setUpdated(new \DateTime("Africa/Kinshasa"));
    $em->merge($inventoryProduct);
    $em->flush();
    $view->setData($inventoryProduct);
    $view->getContext()->setGroups(array(
      'inventory'
    ));
    $view->setHeader('Access-Control-Allow-Origin', '*');
    return $handler->handle($view);
  }
}