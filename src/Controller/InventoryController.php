<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use FOS\RestBundle\View\ViewHandlerInterface;
use App\Service\InventoryHandler;
use App\Entity\Inventory;
use App\Entity\InventoryProduct;

class InventoryController extends AbstractController
{
  /**
   * @Rest\Get("/inventories")
   *
   * @return Response
   */
  public function getAll(ViewHandlerInterface $handler)
  {
    $inventories = $this->getDoctrine()->getRepository('App:Inventory')->findAllAsc();
    $view = View::create();
    $view->setData($inventories);
    $view->setHeader('Access-Control-Allow-Origin','*');
    $view->getContext()->setGroups(array(
      'list_inventories'
    ));
    return $handler->handle($view);
  }

  /**
   * @Rest\Get("/inventory/{id}")
   *
   * @return Response
   */
  public function getOne(Request $request, ViewHandlerInterface $handler)
  {
    $inventories = $this->getDoctrine()->getRepository('App:Inventory')->find($request->get('id'));
    $view = View::create();
    $view->setData($inventories);
    $view->setHeader('Access-Control-Allow-Origin','*');
    $view->getContext()->setGroups(array(
      'inventory'
    ));
    return $handler->handle($view);
  }

  /**
   * @Rest\Post("/inventory")
   *
   * @return Response
   */
  public function create(InventoryHandler $inventoryHandler)
  {
    $em = $this->getDoctrine()->getManager();
    $view = View::create();
    $inventory = $inventoryHandler->createInventory();
    $handler = $this->get('fos_rest.view_handler');
    $view->setData($inventory);
    $view->setHeader('Access-Control-Allow-Origin','*');
    $view->getContext()->setGroups(array(
      'inventory'
    ));
    return $handler->handle($view);
  }

  /**
   * @Rest\Put("inventory/{id}")
   * 
   * @return Response
   */
  public function update(Request $request, ViewHandlerInterface $handler){
    $inventory = $this->getDoctrine()->getRepository(Inventory::class)
    ->findWithProductCodebarreAndType($request->get('id'), $request->get('codebarre'), $request->get('types'));

    if(!$inventory || !$inventory->getInventoryProducts() || count($inventory->getInventoryProducts()) === 0){
      return new Response("No Product found", Response::HTTP_NOT_FOUND);
    }

    $product = null;

    foreach ($inventory->getInventoryProducts() as $ip) {
      if(!$ip->getStatus()){
        $product = $ip;
        break;
      }
    }

    if(!$product) return new Response("Product Scanned", Response::HTTP_NOT_FOUND);

    $product->setStatus(true)->setUpdated(new \DateTime());

    $em = $this->getDoctrine()->getManager();
    $em->persist($product);
    $em->flush();
    
    $view = View::create();
    $view->setData($inventory);
    $view->setHeader('Access-Control-Allow-Origin', '*');
    $view->getContext()->setGroups(array(
      'inventory'
    ));
    
    return $handler->handle($view);
  }
}
