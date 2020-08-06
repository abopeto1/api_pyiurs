<?php

namespace App\Controller;

use App\Entity\BilanBudget;
use App\Entity\Bill;
use App\Entity\Budget;
use App\Entity\CashIn;
use App\Entity\Debit;
use App\Entity\Expence;
use App\Entity\ExpenceCompte;
use App\Entity\Service;
use App\Entity\Credit;
use App\Entity\Customer;
use App\Entity\CustomerCategorie;
use App\Entity\Segment;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcher;
use Symfony\Component\HttpFoundation\JsonResponse;
use JMS\Serializer\SerializationContext as Context;
use Symfony\Component\HttpFoundation\Request;

class DashboardController extends AbstractController
{
    /**
     * @Rest\Get("/dashboards")
     * @Rest\QueryParam(name="sell_activity",default=false)
     * @Rest\QueryParam(name="pat_activity",default=false)
     * @Rest\QueryParam(name="month",default=false)
     * @Rest\QueryParam(name="value",nullable=true)
     */
    public function index(ParamFetcher $paramFetcher)
    {
        $sell_activity = $paramFetcher->get('sell_activity');
        $pat_activity = $paramFetcher->get('pat_activity');
        $month = $paramFetcher->get('month');
        $value = $paramFetcher->get('value');
        $date = date('Y-m-d'); $prevMonth = date('Y-m-d', strtotime("last month"));
        $prevYear = date('Y-m-d', strtotime("last year"));

        if($sell_activity){
            $b = 0; $p_b = 0; $y_b = 0;
            // Vente
            $bills = $this->getDoctrine()->getRepository(Bill::class)->findByMonth($date);
            $previousBills = $this->getDoctrine()->getRepository(Bill::class)->findByMonth($prevMonth);
            $prevYearBills = $this->getDoctrine()->getRepository(Bill::class)->findByInterval(date('Y-m-01', strtotime("last year")),$prevYear);
            $bilan_vente = $this->getDoctrine()->getRepository(BilanBudget::class)->findOneWithBudgetAndDate(1,$date);

            foreach ($bills as $bill) {
                if($bill->getTypePaiement()->getId() == 2){
                    $b += $bill->getAccompte();
                } else {
                    $b += $bill->getNet();
                }
            }

            foreach ($previousBills as $p_bill) {
                if ($p_bill->getTypePaiement()->getId() == 2) {
                    $p_b += $p_bill->getAccompte();
                } else {
                    $p_b += $p_bill->getNet();
                }
            }

            foreach ($prevYearBills as $yBill) {
                if ($yBill->getTypePaiement()->getId() == 2) {
                    $y_b += $yBill->getAccompte();
                } else {
                    $y_b += $yBill->getNet();
                }
            }

            $dashboard['id'] = 1;
            $dashboard['name'] = "Ventes";
            $dashboard['value'][0]['name'] = "Vente";
            $dashboard['value'][0]['month'] = intval($b);
            $dashboard['value'][0]['previous_month'] = intval($p_b);
            $dashboard['value'][0]['last_year'] = intval($y_b);
            $dashboard['value'][0]['budget'] = $bilan_vente ? $bilan_vente->getValue() : 0;

            // Autres Revenus
            $c = 0; $c_m = 0; $c_y = 0;
            $cashins = $this->getDoctrine()->getRepository(CashIn::class)->findByMonth($date);
            $services = $this->getDoctrine()->getRepository(Service::class)->findWithMonth($date);
            $prev_cashins = $this->getDoctrine()->getRepository(CashIn::class)->findByMonth($prevMonth);
            $prev_services = $this->getDoctrine()->getRepository(Service::class)->findWithMonth($prevMonth);
            $prev_year_cashins = $this->getDoctrine()->getRepository(CashIn::class)->findByMonth($prevYear);
            $prev_year_services = $this->getDoctrine()->getRepository(Service::class)->findWithMonth($prevYear);
            $bilan_cash = $this->getDoctrine()->getRepository(BilanBudget::class)->findOneWithBudgetAndDate(2, $date);

            foreach ($cashins as $cash) {
                if ($cash->getCurrency() == "CDF") {
                    $c += $cash->getAmount()/$cash->getTaux();
                } else {
                    $c += $cash->getAmount();
                }
            }
            foreach ($services as $service) {
                if ($service->getCurrency() == "CDF") {
                    $c += $service->getAmount() / $service->getTaux();
                } else {
                    $c += $service->getAmount();
                }
            }
            foreach ($prev_cashins as $cash) {
                if ($cash->getCurrency() == "CDF") {
                    $c_m += $cash->getAmount() / $cash->getTaux();
                } else {
                    $c_m += $cash->getAmount();
                }
            }
            foreach ($prev_services as $service) {
                if ($service->getCurrency() == "CDF") {
                    $c_m += $service->getAmount() / $service->getTaux();
                } else {
                    $c_m += $service->getAmount();
                }
            }
            foreach ($prev_year_services as $service) {
                if ($service->getCurrency() == "CDF") {
                    $c_y += $service->getAmount() / $service->getTaux();
                } else {
                    $c_y += $service->getAmount();
                }
            }

            $dashboard['value'][1]['name'] = "Autres Revenus";
            $dashboard['value'][1]['month'] = intval($c);
            $dashboard['value'][1]['previous_month'] = intval($c_m);
            $dashboard['value'][1]['last_year'] = $c_y;
            $dashboard['value'][1]['budget'] = $bilan_cash ? $bilan_cash->getValue() : 0;

            // Expences
            $e = 0; $e_m = 0; $e_y = 0; $e_b = 0;
            $expences = $this->getDoctrine()->getRepository(Expence::class)->findByMonth($date);
            $prev_expences = $this->getDoctrine()->getRepository(Expence::class)->findByMonth($prevMonth);
            $prev_year_expences = $this->getDoctrine()->getRepository(Expence::class)->findByMonth($prevYear);
            $budgets = $this->getDoctrine()->getRepository(Budget::class)->findWithMonth($date);

            foreach ($expences as $expence) {
                if ($expence->getCurrency() == "CDF") {
                    $taux = $expence->getTaux() == 0 ? 1900 : $expence->getTaux();
                    $e += $expence->getMontant() / $taux;
                } else {
                    $e += $expence->getMontant();
                }
            }
            foreach ($prev_expences as $expence) {
                if ($expence->getCurrency() == "CDF") {
                    $taux = $expence->getTaux() == 0 ? 1900 : $expence->getTaux();
                    $e_m += $expence->getMontant() / $taux;
                } else {
                    $e_m += $expence->getMontant();
                }
            }
            foreach ($prev_year_expences as $expence) {
                if ($expence->getCurrency() == "CDF") {
                    $e_y += $expence->getMontant() / $expence->getTaux();
                } else {
                    $e_y += $expence->getMontant();
                }
            }
            foreach ($budgets as $budget){
                $e_b += $budget->getValue();
            }

            $dashboard['value'][2]['name'] = "Dépense";
            $dashboard['value'][2]['month'] = intval($e);
            $dashboard['value'][2]['previous_month'] = intval($e_m);
            $dashboard['value'][2]['last_year'] = intval($e_y);
            $dashboard['value'][2]['budget'] = intval($e_b);
            // $serializer = \JMS\Serializer\SerializerBuilder::create()->build();
            // $context = new Context();
            // $prevB = $serializer->serialize($previousBills, 'json', $context->setGroups('list_bills'));

            return new JsonResponse([$dashboard]);
        }

        if($pat_activity){
            $pat = 0; $achat = 0;
            $bills = $this->getDoctrine()->getRepository(Bill::class)->findByMonth($date);
            $compte_achat = $this->getDoctrine()->getRepository(ExpenceCompte::class)->findByCompteAndMonth($compte, $date);

            foreach ($bills as $bill) {
                foreach($bill->getBillDetails() as $bd){
                    $product = $bd->getProduct();
                    $pat += $product->getPu() + $product->getCaa();
                }
            }

            foreach($compte_achat->getExpences() as $expence){
                if($expence->getCurrency() == "CDF"){
                    $achat += $expence->getAmount()/$expence->getTaux();
                } else {
                    $achat += $expence->getAmount();
                }
            }

            $dashboard['id'] = 2;
            $dashboard['value'][0]['name'] = "PAT";
            $dashboard['value'][0]['total'] = intval($pat);

            $dashboard['value'][1]['name'] = "Achats Marchandises";
            $dashboard['value'][1]['total'] = intval($achat);

            return new JsonResponse([$dashboard]);
        }

        if($month && $value == 'cash'){
            $dashboard['id'] = $month;
            // Gestation Cash
            $bills = $this->getDoctrine()->getRepository(Bill::class)->findByMonth($month);
            $services = $this->getDoctrine()->getRepository(Service::class)->findWithMonth($month);
            $credits = $this->getDoctrine()->getRepository(Credit::class)->findByMonth($month);

            $billCash = 0;
            $billCredit = 0;
            $billPaiement = 0;
            $billExchange = 0;
            $service = 0;
            $creditDivers = 0;
            $creditBank = 0;

            foreach ($services as $s) {
                if ($s->getCurrency() == "CDF") {
                    $service += $s->getAmmount() / $s->getTaux();
                } else {
                    $service += $s->getAmount();
                }
            }

            foreach ($bills as $bill) {
                switch ($bill->getTypePaiement()->getId()) {
                    case 1:
                        $billCash += $bill->getNet();
                        break;
                    case 2:
                        $billCredit += $bill->getAccompte();
                        break;
                    case 3:
                        $billPaiement += $bill->getNet();
                        break;
                    case 4:
                        $billExchange += $bill->getNet();
                        break;
                    default:
                        break;
                }
            }

            foreach ($credits as $credit) {
                $taux = $credit->getTaux() || 1900;
                $cr = 0;
                $tauxEuro = $credit->getTauxEuro();

                if ($credit->getCurrency() == 'CDF') {
                    $cr = $credit->getAmount() / $taux;
                } else if ($credit->getCurrency() == 'EUR') {
                    $cr = $credit->getAmount() / $tauxEuro;
                } else {
                    $cr = $credit->getAmount();
                }

                if ($credit->getType() == 'divers') {
                    $creditDivers += $cr;
                }
                if ($credit->getType() == 'bank') {
                    $creditBank += $cr;
                }
            }

            $dashboard[$value]['Cash'] = $billCash;
            $dashboard[$value]['Credit'] = $billCredit;
            $dashboard[$value]['Paiement'] = $billPaiement;
            $dashboard[$value]['Exchange'] = $billExchange;
            $dashboard[$value]['Service'] = $service;
            $dashboard[$value]['Crédit Divers'] = $creditDivers;
            $dashboard[$value]['Crédit Banque'] = $creditBank;

            return new JsonResponse([$dashboard]);
        }

        if($month && $value == 'expence'){
            $dashboard['id'] = $month.'expence';
            // Sortie Cash

            $expences = $this->getDoctrine()->getRepository(Expence::class)->findByMonth($month);
            $debits = $this->getDoctrine()->getRepository(Debit::class)->findByMonth($month);
            $totalExpence = 0;
            $debit = 0;

            foreach ($expences as $expence) {
                $taux = $expence->getTaux() || 1900;
                if ($expence->getCurrency() == 'CDF') {
                    $totalExpence += $expence->getMontant() / $taux;
                } else {
                    $totalExpence += $expence->getMontant();
                }
            }

            foreach ($debits as $d) {
                if ($d->getCurrency() == "CDF") {
                    $taux = $d->getTaux() || 1900;
                    $debit += $d->getAmount() / $taux;
                } else {
                    $debit += $d->getAmount();
                }
            }

            $dashboard[$value]['Dépense'] = $totalExpence;
            $dashboard[$value]['Débit'] = $debit;
            return new JsonResponse([$dashboard]);
        }

        if($month && $value == 'stock'){
            $dashboard['id'] = $month.$value;

            // Stock
            $date = new \DateTime($month);
            $segments = $this->getDoctrine()->getRepository(Segment::class)->findByStock();
            $openStock = 0; $addStock = 0; $sellStock = 0; $endStock = 0;

            foreach($segments as $segment){
                if($segment->getProducts()){
                    foreach($segment->getProducts() as $product){
                        $stock = $product->getStock();
                        $billDetail = $product->getBillDetails()->get(count($product->getBillDetails()) -1 );
                        if(
                            ($stock->getAvailable() && $stock->getCreated()->format('Ym') < $date->format('Ym')) ||
                            (
                                !$stock->getAvailable() && 
                                ( $billDetail && $billDetail->getCreated()->format('Ym') == $date->format('Ym'))
                            )
                        ){
                            $openStock += 1;
                        }
                        if($stock->getCreated()->format('Ym') == $date->format('Ym')){
                            $addStock += 1;
                        }
                        if(!$stock->getAvailable() && $billDetail && $billDetail->getCreated()->format('Ym') == $date->format('Ym')){
                            $sellStock += 1;
                        }
                        if(
                            ($stock->getAvailable() && $stock->getCreated()->format('Ym') <= $date->format('Ym')) ||
                            ( !$stock->getAvailable() && (
                                $billDetail && $billDetail->getCreated()->format('Ym') > $date->format('Ym')
                            ))
                        ){
                            $endStock += 1;
                        }
                    }
                }
            }

            // return

            $dashboard[$value]['Stock D\'Ouverture'] = $openStock;
            $dashboard[$value]['Stock Ajouté'] = $addStock;
            $dashboard[$value]['Stock Vendu'] = $sellStock;
            $dashboard[$value]['Stock De Fermeture'] = $endStock;
            return new JsonResponse([$dashboard]);
        }

        if ($month && $value == 'customer') {
            $dashboard['id'] = $month . $value;
            $sell = [0,0,0];
            // Customer
            $date = new \DateTime($month);
            $customers = $this->getDoctrine()->getRepository(Customer::class)->findByMonth($month);
            $newCustomers = []; $oldCustomers = [];
            $monthCustomers = array_filter($customers,function($customer) use ($date){
                $status = false;
                foreach ($customer->getBills() as $bill) {
                    if($bill->getCreated()->format('Ym') == $date->format('Ym')){
                        $status = true;
                        break;
                    }
                }
                return $status;
            });

            foreach ($customers as $customer) {
                if($customer->getCreated() && $customer->getCreated()->format('Ym') == $date->format('Ym')){
                    array_merge($newCustomers, [$customer]);
                    foreach ($customer->getBills() as $bill) {
                        if ($bill->getCreated()->format('Ym') == $date->format('Ym')) {
                            if ($bill->getTypePaiement()->getId() == 2) {
                                $sell[1] += $bill->getAccompte();
                            } else {
                                $sell[1] += $bill->getNet();
                            }
                        }
                    }
                }
                if ($customer->getCreated() == null || ($customer->getCreated()->format('Ym') < $date->format('Ym'))) {
                    array_merge($oldCustomers, [$customer]);
                    foreach ($customer->getBills() as $bill) {
                        if ($bill->getCreated()->format('Ym') == $date->format('Ym')) {
                            if ($bill->getTypePaiement()->getId() == 2) {
                                $sell[2] += $bill->getAccompte();
                            } else {
                                $sell[2] += $bill->getNet();
                            }
                        }
                    }
                }
            }

            foreach ($monthCustomers as $customer) {
                foreach ($customer->getBills() as $bill) {
                    if ($bill->getCreated()->format('Ym') == $date->format('Ym')) {
                        if($bill->getTypePaiement()->getId() == 2){
                            $sell[0] += $bill->getAccompte();
                        } else {
                            $sell[0] += $bill->getNet();
                        }
                    }
                }
            }

            // return

            $dashboard[$value][0]['Gestion des clients'] = "Base de client 30jrs";
            $dashboard[$value][0]['% '] = intval((count($monthCustomers)/count($customers)) * 100);
            $dashboard[$value][0]['# '] = count($monthCustomers);
            $dashboard[$value][0]['Valeur d’achat'] = intval($sell[0]);
            $dashboard[$value][0]['ARPU'] = count($monthCustomers) == 0 ? 0 : intval($sell[0]/count($monthCustomers));

            $dashboard[$value][1]['Gestion des clients'] = "Nouveau Client";
            $dashboard[$value][1]['% '] = intval((count($newCustomers) / count($customers)) * 100);
            $dashboard[$value][1]['# '] = count($newCustomers);
            $dashboard[$value][1]['Valeur d’achat'] = intval($sell[1]);
            $dashboard[$value][1]['ARPU'] = count($newCustomers) == 0 ? 0 : intval($sell[1] / count($newCustomers));

            $dashboard[$value][2]['Gestion des clients'] = "Ancien Client";
            $dashboard[$value][2]['% '] = intval((count($oldCustomers) / count($customers)) * 100);
            $dashboard[$value][2]['# '] = count($oldCustomers);
            $dashboard[$value][2]['Valeur d’achat'] = intval($sell[2]);
            $dashboard[$value][2]['ARPU'] = count($oldCustomers) == 0 ? 0 : intval($sell[2] / count($oldCustomers));

            return new JsonResponse([$dashboard]);
        }

        if($month && $value == 'customer_categorie'){
            $dashboard['id'] = $month . $value;
            $date = new \DateTime($month);
            
            $categories = $this->getDoctrine()->getRepository(CustomerCategorie::class)->findWithSellMonth($month);
            $i = 0;
            foreach($categories as $categorie){
                $dashboard[$value][$i]['Catégorie'] = $categorie->getName();

            //    foreach ($categorie->getCustomers() as $customer) {
            //        foreach ($customer->getCustomerLogs() as $log) {
            //            if($log->getCreated()->format() == $date->format('Ym')){

            //            }
            //        }
            //    }
                
                $dashboard[$value][$i]['Base d\'Ouverture'] = count($categorie->getCustomers());
                $dashboard[$value][$i]['Base Entrée'] = count($categorie->getCustomers());
                $dashboard[$value][$i]['Base Sortie'] = count($categorie->getCustomers());
                $dashboard[$value][$i]['Base de Fermeture'] = count($categorie->getCustomers());
                $i++;
            }

            return new JsonResponse([$dashboard]);
        }

        if ($month && $value == 'kpis') {
            $dashboard['id'] = $month. $value;

            // KPIS
            $bills = $this->getDoctrine()->getRepository(Bill::class)->findByMonth($month);
            $total= 0;
            $dates = [];

            foreach ($bills as $bill) {
                $dates[] = $bill->getCreated()->format('d');
                if ($bill->getTypePaiement()->getId() == 2) {
                    $total += $bill->getAccompte();
                } else {
                    $total += $bill->getNet();
                }
            }

            $dashboard[$value]['Moyenne Vente Par Jour'] = $total == 0 ? 0 : intval($total/(max($dates)));
            $dashboard[$value]['Nombres de Factures'] = count($bills);
            $dashboard[$value]['Moyenne Facture Par Jour'] = $count($bills) == 0 ? 0 : intval($count($bills)/max(dates));

            return new JsonResponse([$dashboard]);
        }
        return date('Y-m-01',strtotime("last year"));
    }

    /**
     * @Rest\Get("/dashboard/{id}")
     */
    public function one(Request $request){
        $id = intval($request->get('id'));
        $date = date('Y-m-d');
        $prevMonth = date('Y-m-d', strtotime("last month"));
        $prevYear = date('Y-m-d', strtotime("last year"));

        if ($id === 2) {
            $pat = 0; $achat = 0;
            $bills = $this->getDoctrine()->getRepository(Bill::class)->findByMonth($date);
            $compte_achat = $this->getDoctrine()->getRepository(ExpenceCompte::class)->findByCompteAndMonth(2, $date);

            foreach ($bills as $bill) {
                foreach ($bill->getBillDetails() as $bd) {
                    $product = $bd->getProduct();
                    $pat += $product->getPu() + $product->getCaa();
                }
            }

            if($compte_achat){
                foreach ($compte_achat->getExpences() as $expence) {
                    if ($expence->getCurrency() == "CDF") {
                        $achat += $expence->getMontant() / $expence->getTaux();
                    } else {
                        $achat += $expence->getMontant();
                    }
                }
            }

            $dashboard['id'] = 2;
            $dashboard['value'][0]['name'] = "PAT";
            $dashboard['value'][0]['total'] = intval($pat);

            $dashboard['value'][1]['name'] = "Achats Marchandises";
            $dashboard['value'][1]['total'] = intval($achat);

            return new JsonResponse($dashboard);
        }

        if($id == 3){
            $bills = $this->getDoctrine()->getRepository(Bill::class)->findByMonth($date);
            $average_by_order = 0; $total = 0; $countBills = count($bills);
            foreach($bills as $bill){
                if($bill->getTypePaiement()->getId() == 2){
                    $total += $bill->getAccompte();
                } else {
                    $total += $bill->getNet();
                }
            }
            $average_by_order = $countBills == 0 ? 0 : intval($countBills/ intval(date('d')));
            $average_by_day = $total / intval(date('d'));

            $dashboard['id'] = 3;
            $dashboard['value'][0]['name'] = "Moyenne de Vente par Facture";
            $dashboard['value'][0]['total'] = intval($average_by_order);

            $dashboard['value'][1]['name'] = "Moyenne de Vente par Jour";
            $dashboard['value'][1]['total'] = intval($average_by_day);

            return new JsonResponse($dashboard);
        }
        return $id;
    }
}
