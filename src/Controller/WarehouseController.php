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
use App\Entity\Warehouse;
use App\Entity\Product;
use App\Entity\ProductStock;
use App\Entity\Type;
use App\Entity\Segment;
use App\Form\WarehouseType;
use App\Form\ProductWarehouseType;

class WarehouseController extends Controller
{
    /**
     * List all Warehouse
     * @Rest\Get("/warehouses")
     */
    public function getWarehouseAction()
    {
      $warehouses = $this->getDoctrine()->getRepository(Warehouse::class)->findAll();

      foreach($warehouses as $warehouse){
        $loaded = []; $notLoaded = [];
        $value = [
          'loadedValue' => 0, 'loadedSellValue' => 0,
          'notLoadedValue' => 0, 'notLoadedSellValue' => 0,
        ];
        foreach ($warehouse->getProducts() as $product) {

          $stock = $product->getStock();
          if($stock && $stock->getAvailable()){
            $loaded = array_merge($loaded, [$product]);
            $value['loadedValue'] += intval($product->getPu() + $product->getCaa());
            $value['loadedSellValue'] += intval($product->getPv());
          }

          if(!$stock || (!$stock->getAvailable() && $product->getMoveStatus() === 0)){
            $notLoaded = array_merge($notLoaded, [$product]);
            $value['notLoadedValue'] += intval($product->getPu() + $product->getCaa());
            $value['notLoadedSellValue'] += intval($product->getPv());
          }
        }
        
        $warehouse
            ->setLoadedQte(count($loaded))->setLoadedValue($value['loadedValue'])
            ->setLoadedSellValue($value['loadedSellValue'])
            ->setNotLoadedQte(count($notLoaded))->setNotLoadedValue($value['notLoadedValue'])
            ->setNotLoadedSellValue($value['notLoadedSellValue']);
      }

      $view = View::create();
      $handler = $this->get('fos_rest.view_handler');
      $view->setData($warehouses);
      $view->getContext()->setGroups(array(
        'warehouses'
      ));
      $view->setHeader('Access-Control-Allow-Origin','*');
      return $handler->handle($view);
    }

    /**
     * List one Warehouse
     * @Rest\Get("/warehouse/{id}")
     */
    public function getOneWarehouseAction(Request $request)
    {
      $warehouse = $this->getDoctrine()->getRepository(Warehouse::class)->find($request->get('id'));
      $view = View::create();
      $handler = $this->get('fos_rest.view_handler');
      $view->setData($warehouse);
      $view->getContext()->setGroups(array(
        'one_warehouse'
      ));
      $view->setHeader('Access-Control-Allow-Origin','*');
      return $handler->handle($view);
    }

    /**
     * create a warehouse
     * @Rest\Post("/warehouse")
     *
     * @return Response
     */
    public function createWarehouseAction(Request $request)
    {
      $em = $this->getDoctrine()->getManager();
      $view = View::create();
      $handler = $this->get('fos_rest.view_handler');
      $warehouse = new Warehouse();
      $form = $this->createForm(WarehouseType::class, $warehouse);
      $form->submit($request->request->all());
      if($form->isValid()){
        $em = $this->getDoctrine()->getManager();
        $warehouse->setCreated(new \DateTime());
        $warehouse->setReference(date('YmdHis'));
        $em->persist($warehouse);
        $em->flush();
        $view->setData($warehouse);
        $view->getContext()->setGroups(array(
          'warehouses'
        ));
        $view->setHeader('Access-Control-Allow-Origin','*');
        return $handler->handle($view);
      } else {
        return $form;
      }
    }

    /**
     * update a warehouse
     * @Rest\Put("/warehouse/{id}")
     *
     * @return Response
     */
    public function updateWarehouseAction(Request $request)
    {
      $em = $this->getDoctrine()->getManager();
      $warehouse = $this->getDoctrine()->getRepository(Warehouse::class)->find($request->get('id'));
      if(empty($warehouse)){
        return new Response("Warehouse Not Found", Response::HTTP_NOT_FOUND);
      }
      $ids = $request->get('productTransfers');

      if(!empty($ids) && count($ids) > 0){
        foreach ($ids as $id) {
          $product = $this->getDoctrine()->getRepository(Product::class)->find($id);
          $stock = new ProductStock();
          $stock->setCreated(new \DateTime());
          $stock->setQte(1);
          $stock->setOutQte(0);
          $stock->setAvailable(true);
          $em->persist($stock);
          $product->setMoveStatus(1);
          $product->setStock($stock);
          $em->persist($product);
        }
        $em->persist($warehouse);

        $view = View::create();
        $handler = $this->get('fos_rest.view_handler');
        $em->flush();
        $view->setData($warehouse);
        $view->setStatusCode(Response::HTTP_CREATED);
        $view->getContext()->setGroups(array(
          'warehouses'
        ));
        $view->setHeader('Access-Control-Allow-Origin','*');
        return $handler->handle($view);
      } else {
        $view = View::create();
        $handler = $this->get('fos_rest.view_handler');
        $body = json_decode($request->getContent(),true);
        $i = 1;
        foreach($body as $p){
          $product = new Product;
          $form = $this->createForm(ProductWarehouseType::class, $product);

          $segment = $this->getDoctrine()->getRepository(Segment::class)->findOneByName($p['segment']);
          if(empty($segment)){
            return new Response("Unknow Segment on Line $i", Response::HTTP_BAD_REQUEST);
          }

          $type = $this->getDoctrine()->getRepository(Type::class)->findByNameAndSegment($p['type'],$segment->getId());
          if(empty($type)){
            return new Response("Unknow Type on Line $i", Response::HTTP_BAD_REQUEST);
          }

          $p['type'] = $type->getId(); $p['segment'] = $segment->getId();
          $form->submit($p);
          if($form->isValid()){
            $product->setCreated(new \DateTime());
            $product->setMoveStatus(0);
            $em->persist($product);
            $warehouse->addProduct($product);
          } else {
            return new Response("Something Wrong", Response::HTTP_BAD_REQUEST);
          }
        }
        $em->persist($warehouse);
        $em->flush();
        $view->setData($warehouse);
        $view->setStatusCode(Response::HTTP_CREATED);
        $view->getContext()->setGroups(array(
          'warehouses'
        ));
        $view->setHeader('Access-Control-Allow-Origin','*');
        return $handler->handle($view);
      }
    }
}
