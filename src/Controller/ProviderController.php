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
use App\Entity\Provider;
use App\Form\ProviderType;

class ProviderController extends AbstractController
{
  /**
   * List all providers
   * @Rest\Get("/providers")
   * @Rest\QueryParam(name="credit",description="Toutes les ventes du jour actuel")
   * @Rest\QueryParam(name="debit",description="Toutes les ventes du jour actuel")
   *
   * @return Response
   */
    public function getProvidersAction(ViewHandlerInterface $handler, ParamFetcher $paramFetcher)
    {
      $credit = $paramFetcher->get('credit'); $providers = null;
      $debit = $paramFetcher->get('debit');

      if(!empty($credit)){
        $providers = $this->getDoctrine()->getRepository(Provider::class)->findWithCredit($credit);
      } else if($debit){
        $providers = $this->getDoctrine()->getRepository(Provider::class)->findWithDebit();
      } else {
        $providers = $this->getDoctrine()->getRepository(Provider::class)->findAll();
      }
      $view = View::create();
      $view->setData($providers);
      $view->setHeader('Access-Control-Allow-Origin','*');
      $view->getContext()->setGroups(array(
        'provider'
      ));
      return $handler->handle($view);
    }

    /**
     * create a provider
     * @Rest\Post("/provider")
     *
     * @return Response
     */
    public function createProviderAction(Request $request, ViewHandlerInterface $handler)
    {
      $view = View::create();
      $provider = new Provider();
      $form = $this->createForm(ProviderType::class, $provider);
      $form->submit($request->request->all());
      if($form->isValid()){
        $em = $this->getDoctrine()->getManager();
        $provider->setCode($this->giveAcode($provider->getName()));
        $provider->setCreated(new \DateTime());
        $em->persist($provider);
        $em->flush();
        $view->setData($provider);
        $view->getContext()->setGroups(array(
          'provider'
        ));
        $view->setHeader('Access-Control-Allow-Origin','*');
        return $handler->handle($view);
      } else {
        return $form;
      }
    }

    private function giveAcode($param){
      $init = explode(" ",$param);$code ="";
  		foreach($init as $ind => $val){
  			$code .= strtoupper($val[0]);
  		}
      $nbr = rand(); $code .= $nbr;
  		return $code;
  	}
}
