<?php

namespace App\Service;

use App\Entity\Client;
use App\Repository\ServiceRepository ;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


class ClientExcelService
{
    public function __construct(
        private string $appTmp
        )
    {}
    public function getExcel(?Client $client,ServiceRepository $serviceRepository)
    {
        if(is_null($client)){
            throw new Exception('Client data missing');
        }
        $fileName = $this->appTmp.uniqid($client->getName()).'.xlsx';
        $spreadsheet = new Spreadsheet();
        $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(5);
        $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(10);
        $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(35);
        $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(8);
        $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(40);
        $activeWorksheet = $spreadsheet->getActiveSheet();
        $i=1;
        /*
         * SET EXCEL HEAD - Client Point name
         */
        $activeWorksheet->setCellValue('A'.$i, $client->getName()." (".$client->getNin().")");
        $activeWorksheet->MergeCells('A1:E1');
        $i++;
        /*
         * SET EXCEL HEAD - table title
         */
        $activeWorksheet->setCellValue('A'.$i, "Lp:");
        $activeWorksheet->setCellValue('B'.$i, "Data:");
        $activeWorksheet->setCellValue('C'.$i, "Punkt:");
        $activeWorksheet->setCellValue('D'.$i, "Czas(h):");
        $activeWorksheet->setCellValue('E'.$i, "Opis:");
        /*
         * SET EXCEL BODY
         */
        $i++;
        $lp=1;
        foreach($serviceRepository->findByClientId($client->getId(),'ASC') as $value){
            //dd($value);
            //$value->getEndedAt()
            $activeWorksheet->setCellValue('A'.$i, $lp++);
            $activeWorksheet->setCellValue('B'.$i, $value->getEndedAt()->format('Y-m-d'));
            $activeWorksheet->setCellValue('C'.$i, $value->getClientPoint()->getName());
            $activeWorksheet->setCellValue('D'.$i, $value->getTime());
            $activeWorksheet->setCellValue('E'.$i, $value->getDescription());
            $i++;
        }
        
        $writer = new Xlsx($spreadsheet);
        $writer->save($fileName);
        $fileContent = file_get_contents($fileName);
        unlink($fileName);
        return $fileContent;
    }
}