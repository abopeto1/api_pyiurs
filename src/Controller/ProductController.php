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
use App\Entity\Product;
use App\Entity\SoldOut;
use App\Entity\Warehouse;

class ProductController extends Controller
{
  /**
   * List products
   * @Rest\Get("/products")
   * @Rest\QueryParam(name="available",description="Type stock en boutique ou entrepots",default="")
   * @Rest\QueryParam(name="type",description="Type stock en boutique ou entrepots")
   * @Rest\QueryParam(name="stock",description="Type stock en boutique ou entrepots",default="")
   * @Rest\QueryParam(name="stock_resume",description="Type stock en boutique ou entrepots",default="")
   * @Rest\QueryParam(name="codebarre",description="Type stock en boutique ou entrepots",default="")
   * @Rest\QueryParam(name="in_stock_transfert",description="Type stock en entrepot",default="")
   * @Rest\QueryParam(name="warehouse",description="Type stock en entrepot",default="")
   * @Rest\QueryParam(name="q",description="Type stock en boutique ou entrepots",nullable=true)
   * @Rest\QueryParam(name="order",description="Type stock en boutique ou entrepots",default="asc")
   * @Rest\QueryParam(name="offset",description="Type stock en entrepot",default="0")
   * @Rest\QueryParam(name="limit",description="Type stock en entrepot",default="10")
   *
   * @return Response
   */
    public function getProductsAction(ParamFetcher $paramFetcher)
    {
      $stock = $paramFetcher->get('stock'); $codebarre = $paramFetcher->get('codebarre');
      $available = $paramFetcher->get('available');
      $stock_resume = $paramFetcher->get('stock_resume'); $in_stock_transfert = $paramFetcher->get('in_stock_transfert');
      $warehouse = $paramFetcher->get('warehouse'); $type =  $paramFetcher->get('type');

      if($in_stock_transfert !== "" && isset($codebarre) && isset($warehouse)){
        $products = $this->getDoctrine()->getRepository(Product::class)->findByInStock($codebarre);
        $warehouse = $this->getDoctrine()->getRepository(Warehouse::class)->find($warehouse);
        $founds = [];
        if($warehouse){
          foreach ($products as $product) {
            if($warehouse->getProducts()->contains($product)){
              $founds[] = $product;
            }
          }
        } else {
          return new Response("No Warehouse found",Response::HTTP_NOT_FOUND);
        }
        $view = View::create();
        $handler = $this->get('fos_rest.view_handler');
        $view->setData($founds);
        $view->setHeader('Access-Control-Allow-Origin','*');
        $view->getContext()->setGroups(array(
          "products"
        ));
        return $handler->handle($view);
      } else if($stock_resume == true){
        $products = $this->getDoctrine()->getRepository(Product::class)->findByStock();
        $view = View::create();
        $handler = $this->get('fos_rest.view_handler');
        $view->setData($products);
        $view->setHeader('Access-Control-Allow-Origin','*');
        $view->getContext()->setGroups(array(
          "stock_resume"
        ));
        return $handler->handle($view);
      } else if(!empty($type) && $available){
        $products = $this->getDoctrine()->getRepository(Product::class)->findByTypeStockAvailable($type);
        $view = View::create();
        $handler = $this->get('fos_rest.view_handler');
        $view->setData($products);
        $view->setHeader('Access-Control-Allow-Origin','*');
        $view->getContext()->setGroups(array(
          "productByCodebarre"
        ));
        return $handler->handle($view);
      } else if($available !== ""){
        $products = $this->getDoctrine()->getRepository(Product::class)->findByStockAvailable();
        $view = View::create();
        $handler = $this->get('fos_rest.view_handler');
        $view->setData($products);
        $view->setHeader('Access-Control-Allow-Origin','*');
        $view->getContext()->setGroups(array(
          "products"
        ));
        return $handler->handle($view);
      } else if($codebarre != ""){
        $products = $this->getDoctrine()->getRepository(Product::class)->findByCodebare($codebarre);
        $theProduct = [];
        if(count($products) > 0){
          foreach($products as $p){
            if($p->getStock() !== null){
              if($p->getStock()->getAvailable() && $p->getMoveStatus() === 1){
                array_push($theProduct,$p);
              }
            }
          }
          if(count($theProduct) > 0){
            $view = View::create();
            $handler = $this->get('fos_rest.view_handler');
            $view->setData($theProduct);
            $view->getContext()->setGroups(array(
              'productByCodebarre'
            ));
            $view->setHeader('Access-Control-Allow-Origin','*');
            return $handler->handle($view);
          } else {
            return new Response("No Product available for this codebarre",Response::HTTP_NOT_FOUND);
          }
        } else {
          return new Response("No Product for this codebarre",Response::HTTP_NOT_FOUND);
        }
      } else if($stock_resume != ""){
        $products = $this->getDoctrine()->getRepository(Product::class)->findByStock();
        $view = View::create();
        $handler = $this->get('fos_rest.view_handler');
        $view->setData($products);
        $view->setHeader('Access-Control-Allow-Origin','*');
        $view->getContext()->setGroups(array(
          "resume"
        ));
        return $handler->handle($view);
      } else {
        $products = $this->getDoctrine()->getRepository(Product::class)->search(
          $paramFetcher->get('q'), $paramFetcher->get('order'), $paramFetcher->get('limit'),
          $paramFetcher->get('offset')
        );

        $view = View::create();
        $handler = $this->get('fos_rest.view_handler');
        $view->setData($products->getCurrentPageResults());
        $view->setHeader('Access-Control-Allow-Origin','*');
        $view->getContext()->setGroups(array(
          'productByCodebarre'
        ));
        return $handler->handle($view);
      }
    }

  /**
   * get one product
   * @Rest\Get("/product/{product_id}")
   *
   * @return Response
   */
    public function getProductAction(Request $request)
    {
      $product = $this->getDoctrine()->getRepository(Product::class)->find($request->get('product_id'));
      $view = View::create();
      $handler = $this->get('fos_rest.view_handler');
      $view->setData($product);
      $view->setHeader('Access-Control-Allow-Origin','*');
      $view->getContext()->setGroups(array(
        'product'
      ));
      return $handler->handle($view);
    }

  /**
   * create a product
   * @Rest\Post("/products")
   *
   * @return Response
   */
  public function createProductAction(Request $request)
  {
    $em = $this->getDoctrine()->getManager();
    $body = json_decode($request->getContent(),true);
    $view = View::create();
    $handler = $this->get('fos_rest.view_handler');
    $products = $this->getDoctrine()->getRepository(Product::class)->findByCodebare($body['codebarre']);
    $sold = $this->getDoctrine()->getRepository(SoldOut::class)->find($body['promotion']);
    $product = null;
    if(count($products) > 0){
      foreach($products as $p){
        if($p->getStock() !== null){
          if(($p->getStock()->getQte() - $p->getStock()->getOutQte() > 0) && $p->getMoveStatus() === 1){
            if($p->getSold()->getId() !== $body['promotion']){
              $product = $p;
              break;
            }
          }
        }
      }
    }
    if($product !== null){
      $product->setSold($sold);
      $em->persist($product);
      $em->flush();
      $view->setData($product);
      $view->getContext()->setGroups(array(
        'product_one_sold'
      ));
      $view->setHeader('Access-Control-Allow-Origin','*');
      return $handler->handle($view);
    } else {
      return new Response('NOT_FOUND',Response::HTTP_NOT_FOUND);
    }
  }

}
