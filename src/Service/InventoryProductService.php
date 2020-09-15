<?php

namespace App\Service;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class InventoryProductService
{
    public function __construct(){
        
    }

    public function getExcelFile($inventoryProducts, $filename = "Rapport Inventaire"){
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $i = 2;
        $columns = [
            "ID", "Codebarre", "Segment", "Type", "Catégorie", "Marque", "Couleur", "Taille",
            "Description", "Code Livraison", "Source", "PU", "CAA", "PV",
        ];
        $cols = ["A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N"];
        foreach($columns as $key => $column){
            $sheet->setCellValue($cols[$key].'1', $column);
        }
        
        foreach($inventoryProducts as $inventoryProduct){
            $product = $inventoryProduct->getProduct();
            $sheet
                ->setCellValue("A".$i, $product->getId())
                ->setCellValue("B".$i, $product->getCodebarre())
                ->setCellValue("C".$i, $product->getType()->getSegment()->getName())
                ->setCellValue("D".$i, $product->getType()->getName())
                ->setCellValue("E".$i, $product->getCat())
                ->setCellValue("F".$i, $product->getMarque())
                ->setCellValue("G".$i, $product->getCouleur())
                ->setCellValue("H".$i, $product->getTaille())
                ->setCellValue("I".$i, $product->getDescription())
                ->setCellValue("J".$i, $product->getDelivery()->getName())
                ->setCellValue("K".$i, $product->getSource())
                ->setCellValue("L".$i, $product->getPu())
                ->setCellValue("M".$i, $product->getCaa())
                ->setCellValue("N".$i, $product->getPv())
            ;
            $i++;
        }
        $writer = new Xlsx($spreadsheet);
        $response =  new StreamedResponse(
            function () use ($writer) {
                $writer->save('php://output');
            }
        );
        $response->headers->set('Content-Type', 'application/vnd.ms-excel');
        $response->headers->set('Content-Disposition', 'attachment;filename="'.$filename.'.xls"');
        $response->headers->set('Cache-Control','max-age=0');
        return $response;
    }
}