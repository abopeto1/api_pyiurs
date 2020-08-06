<?php

namespace App\Service;

use Mpdf\Mpdf;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;
use App\Entity\Bill;
use App\Entity\Expence;
use App\Entity\Credit;
use App\Entity\CashIn;
use App\Entity\CustomerCategorie;
use App\Entity\Debit;
use App\Entity\Service;

class GetPdfService
{
  private $_em;
  private $_twig;

  public function __construct(EntityManagerInterface $em, Environment $twig){
    $this->_em = $em;
    $this->_twig = $twig;
  }

  public function createPdf(string $entityName,int $id){
    $entity = $this->_em->getRepository('App:'.ucfirst($entityName))->find($id);
    if($entity){
      if($entityName === 'bill' || $entityName === 'expence'){
        $options = [
          'format' => [58,297],
          'margin_top' => 1,
          'margin_left' => 2,
          'margin_bottom' => 1,
          'margin_right' => 2,
        ];
        $mpdf = new Mpdf($options);
        $file = $entityName.'.html.twig';
        $params[$entityName] = $entity;
        $html = $this->_twig->render($file,$params);
        $mpdf->WriteHTML($html);
        $pdf = $mpdf->Output();
        $response = new Response(
          $pdf,200,array(
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="Facture.pdf"',
          )
        );
        $response->headers->set('Access-Control-Allow-Origin','*');
        return $response;
      } else {
        if($entityName === 'clotureDay'){
          $mpdf = new Mpdf();
          $file = 'cloture.html.twig';

          $billsMonth = $this->_em->getRepository(Bill::class)->findByMonth($entity->getCreated());
          $customerCategories = $this->_em->getRepository(CustomerCategorie::class)->findWithSellDay($entity->getCreated());
          $cashin = $this->_em->getRepository(CashIn::class)->findByMonth($entity->getCreated());
          $credits = $this->_em->getRepository(Credit::class)->findByMonth($entity->getCreated());
          $expences = $this->_em->getRepository(Expence::class)->findByMonth($entity->getCreated());
          $services = $this->_em->getRepository(Service::class)->findWithMonth($entity->getCreated());
          $debits = $this->_em->getRepository(Debit::class)->findByMonth($entity->getCreated());

          $params = [
            'cloture' => $entity, 'billsMonth' => $billsMonth, 'cashin' => $cashin, 'credits' => $credits, 
            'expences' => $expences, 'customerCategories' => $customerCategories,
            'services' => $services, 'debits' => $debits,
          ];

          $html = $this->_twig->render('cloture.html.twig',$params);
          $footer = $this->_twig->render('footer.html.twig');

          $mpdf->SetHTMLFooter();
          $mpdf->WriteHTML($html);
          $pdf = $mpdf->Output();

          $response = new Response(
            $pdf,200,array(
              'Content-Type' => 'application/pdf',
              'Content-Disposition' => "inline; filename='Facture Mois".$entity->getCreated().".pdf'",
            )
          );
          $response->headers->set('Access-Control-Allow-Origin','*');
          return $response;
        } else {
          $mpdf = new Mpdf();
          $file = 'cloture_month/index.html.twig';

          $segments = $this->_em->getRepository("App:Segment")->findByStock();
          $expenceAccountCategories = $this->_em->getRepository("App:ExpenceCompteCategorie")->findByClotureMonth($entity->getId());
          $customers = $this->_em->getRepository("App:Customer")->findAll(); //findByClotureMonth($cloture->getId());

          $params = [
            'cloture' => $entity, 'segments' => $segments, 'expenceAccountCategories' => $expenceAccountCategories,
            'customers' => $customers,
          ];

          $html = $this->_twig->render('cloture_month/index.html.twig', $params);
          $footer = $this->_twig->render('footer.html.twig');

          $mpdf->SetHTMLFooter();
          $array = explode("<br />", $html);
          foreach ($array as $a) {
            $mpdf->WriteHTML($a);
          }
          $pdf = $mpdf->Output("cloture_month_reports/".$entity->getYear()."-".$entity->getMonth().".pdf","F");
          // $pdf = $mpdf->Output();
          
          // $response = new Response(
          //   $pdf,
          //   200,
          //   array(
          //     'Content-Type' => 'application/pdf',
          //     'Content-Disposition' => "inline; filename='Cloture Mois" . $entity->getCreated() . ".pdf'",
          //   )
          // );
          // $response->headers->set('Access-Control-Allow-Origin', '*');
          // return $response;
        }
      }
    }

  }
  
  public function createPdfnot(string $entityName,int $number){
    $mpdf = $options ? new Mpdf($options) : new Mpdf();
    $mpdf->WriteHTML($html);
    return $mpdf->Output();
  }

  /**
   * Create and output a file from the html given and the options if it's not undedined
   * @param $html : twig render html
   * @param array $options: array of option
   */
  public function createClotureDayPdf($html,$options){
    $mpdf = $options ? new Mpdf($options) : new Mpdf();
    $mpdf->WriteHTML($html);
    return $mpdf->Output();
  }
}
