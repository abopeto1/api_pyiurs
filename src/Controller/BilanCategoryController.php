<?php

namespace App\Controller;

use App\Entity\BilanCategory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Annotations\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\JsonResponse;

class BilanCategoryController extends Controller
{
    /**
     * @Rest\Get("/bilan_categories")
     * @Rest\QueryParam(name="date",description="Toutes les ventes du mois",default="")
     */
    public function index(ParamFetcher $paramFetcher)
    {
        $date = $paramFetcher->get('date'); $bilan_categories = null;
        if (!empty($date)) {
            $bilan_categories = $this->getDoctrine()->getRepository(BilanCategory::class)->findWithDate($date);
            foreach ($bilan_categories as $bilan_category) {
                foreach ($bilan_category->getBilanAccounts() as $bilan) {
                    $value = 0;
                    if($bilan->getCode() == "70001"){
                        $bills = $this->getDoctrine()->getRepository("App:Bill")->findByMonth($date);
                        foreach ($bills as $b) {
                            if($b->getTypePaiement()->getId() == 2){
                                $value += intval($b->getAccompte());
                            } else {
                                $value += intval($b->getNet());
                            }
                        }
                    }
                    if ($bilan->getCode() == "70002") {
                        $cashins = $this->getDoctrine()->getRepository("App:CashIn")->findByMonth($date);
                        foreach ($cashins as $c) {
                            if ($c->getCurrency() == "CDF") {
                                $value += intval($c->getAmount()/$c->getTaux());
                            } else if ($c->getCurrency() == "EUR") {
                                $value += intval($c->getAmount() / $c->getTauxEuro());
                            } else {
                                $value += intval($c->getAmount());
                            }
                        }
                    }
                    if ($bilan->getCode() == "70003") {
                        $bills = $this->getDoctrine()->getRepository("App:Bill")->findByMonth($date);
                        foreach ($bills as $b) {
                            foreach ($b->getBillDetails() as $bd) {
                                $value += $bd->getProduct()->getPu() + $bd->getProduct()->getCaa();
                            }
                        }
                    }
                    $bilan->setValueMonth(intval($value));
                }
            }
            $view = View::create();
            $handler = $this->get('fos_rest.view_handler');
            $view->setData($bilan_categories);
            $view->setHeader('Access-Control-Allow-Origin', '*');
            return $handler->handle($view);
        }
    }
}
