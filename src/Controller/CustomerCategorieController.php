<?php

namespace App\Controller;

use App\Entity\CustomerCategorie;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\View\View;
use FOS\RestBundle\View\ViewHandlerInterface;

class CustomerCategorieController extends AbstractController
{
    /**
     * @Rest\Get("/customer_categories")
     * @Rest\QueryParam(name="total",description="get all entries with the date given",default="")
     */
    public function getCustomerCategorieAction(ViewHandlerInterface $handler)
    {
        $categories = $this->getDoctrine()->getRepository(CustomerCategorie::class)->findAll();
        foreach($categories as $category){
            $totalCustomer = count($category->getCustomers());
            $category->setTotalCustomer($totalCustomer);
        }
        $view = View::create();
        $view->setData($categories);
        $view->setHeader('Access-Control-Allow-Origin', '*');
        $view->getContext()->setGroups(array(
            'customer_categories'
        ));
        return $handler->handle($view);
    }
}
