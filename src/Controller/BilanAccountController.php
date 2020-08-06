<?php

namespace App\Controller;

use App\Entity\BilanAccount;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\View\ViewHandlerInterface;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Controller\Annotations as Rest;

class BilanAccountController extends AbstractController
{
    /**
     * @Rest\Get("/bilan_accounts")
     */
    public function index(ViewHandlerInterface $handler)
    {
        $accounts = $this->getDoctrine()->getRepository(BilanAccount::class)->findAll();
        $view = View::create();
        $view->setData($accounts);
        // $view->getContext()->setGroups(array(
        //     "agent"
        // ));
        $view->setHeader('Access-Control-Allow-Origin', '*');
        return $handler->handle($view);
    }
}
