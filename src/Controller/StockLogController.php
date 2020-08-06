<?php

namespace App\Controller;

use App\Entity\StockLog;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Annotations\Route;
use Symfony\Component\Routing\Annotation\Route as Routes;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\View\View;
use FOS\RestBundle\View\ViewHandlerInterface;
use App\Service\ProductService;
use App\Entity\Product;

class StockLogController extends AbstractController
{
    /**
     * Get All Stock Log
     * @Rest\Get("/stock_logs")
     * @Rest\QueryParam(name="month",description="Historique Mois")
     * 
     */
    public function getAll(ParamFetcher $paramFetcher, ProductService $productService, ViewHandlerInterface $handler)
    {
        $month = $paramFetcher->get('month'); $stockLogs = null;
        if(!empty($month)){
            $stockLogs = $this->getDoctrine()->getRepository("App:StockLog")->findWithMonth($month);
        } else {
            $stockLogs = $this->getDoctrine()->getRepository("App:StockLog")->findAll();
        }
        $view = View::create();
        $view->setData($stockLogs);
        $view->setHeader('Access-Control-Allow-Origin','*');
        return $handler->handle($view);
    }

    /**
     * Get today Stock Log
     * @Rest\Get("/stock_log/{id}")
     */
    public function getOne(Request $request, ProductService $productService)
    {
        if($request->get('id') == 0){
            $created = new \DateTime();
            $stats = $productService->getStockSituationActualDay($created);
            return new JsonResponse($stats);
        }
    }
    
    /**
     * Create All Log
     * @Routes("/stocklogcreate", name="stock_log")
     */
    public function createAllLogs(ProductService $productService)
    {
        $clotures = $this->getDoctrine()->getRepository("App:ClotureDay")->findAll();
        $i = 0;
        $em = $this->getDoctrine()->getManager();
        $tm = $this->getDoctrine()->getRepository(Product::class);
        foreach($clotures as $cloture){
            $date = $cloture->getCreated()->format("Y-m-d");
            $stockLog = new StockLog();
            $stockLog->setCreated($cloture->getCreated());
            $statut = ["added", "open", "closed","selled"];
            foreach ($statut as $k) {
                $total = $tm->findByProductPeriodAndStatut($date,$k);
                $val = 0; $pat = 0;
                if($k == "closed"){
                    foreach ($total as $pp) {
                        $val += $pp->getPv();
                        $pat += $pp->getCaa() + $pp->getPu();
                    }
                }
                if($k == "open") $stockLog->setOpen(count($total));
                if ($k == "added") $stockLog->setAdded(count($total));
                if ($k == "selled") $stockLog->setSelled(count($total));
                if ($k == "closed"){
                    $stockLog->setClosed(count($total))->setValueClosed($val)
                    ->setPatValueClosed($pat)->setReturned(0);
                }
            }
            $em->persist($stockLog);
            $em->flush();
        }
        return "ok";
    }

    /**
     * Create All Log
     * @Routes("/stocklogcreate/{date}", name="stock_logs")
     */
    public function createSemiLogs(ProductService $productService, string $date)
    {
        $clotures = $this->getDoctrine()->getRepository("App:ClotureDay")->findWithBeganDate($date);
        $i = 0;
        $em = $this->getDoctrine()->getManager();
        $tm = $this->getDoctrine()->getRepository(Product::class);
        foreach ($clotures as $cloture) {
            $date = $cloture->getCreated()->format("Y-m-d");
            $stockLog = new StockLog();
            $stockLog->setCreated($cloture->getCreated());
            $statut = ["added", "open", "closed", "selled"];
            foreach ($statut as $k) {
                $total = $tm->findByProductPeriodAndStatut($date, $k);
                $val = 0;
                $pat = 0;
                if ($k == "closed") {
                    foreach ($total as $pp) {
                        $val += $pp->getPv();
                        $pat += $pp->getCaa() + $pp->getPu();
                    }
                }
                if ($k == "open") $stockLog->setOpen(count($total));
                if ($k == "added") $stockLog->setAdded(count($total));
                if ($k == "selled") $stockLog->setSelled(count($total));
                if ($k == "closed") {
                    $stockLog->setClosed(count($total))->setValueClosed($val)
                        ->setPatValueClosed($pat)->setReturned(0);
                }
            }
            $em->persist($stockLog);
            $em->flush();
        }
        return "ok";
    }
}
