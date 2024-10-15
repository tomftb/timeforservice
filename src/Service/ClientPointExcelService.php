<?php

namespace App\Service;

use App\Entity\ClientPoint;
use App\Repository\ServiceRepository ;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


class ClientPointExcelService
{
    public function __construct(
        private string $appTmp
        )
    {}
    public function getExcel(?ClientPoint $clientPoint,ServiceRepository $serviceRepository)
    {
        if(is_null($clientPoint)){
            throw new Exception('Client Point data missing');
        }
        $fileName = $this->appTmp.uniqid($clientPoint->getName()).'.xlsx';
        $spreadsheet = new Spreadsheet();
        $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(5);
        $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(10);
        $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(8);
        $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(30);
        $activeWorksheet = $spreadsheet->getActiveSheet();
        $i=1;
        /*
         * SET EXCEL HEAD - Client Point name
         */
        $activeWorksheet->setCellValue('A'.$i, $clientPoint->getName());
        $activeWorksheet->MergeCells('A1:D1');
        $i++;
        /*
         * SET EXCEL HEAD - table title
         */
        $activeWorksheet->setCellValue('A'.$i, "Lp:");
        $activeWorksheet->setCellValue('B'.$i, "Data:");
        $activeWorksheet->setCellValue('C'.$i, "Czas(h):");
        $activeWorksheet->setCellValue('D'.$i, "Opis:");
        /*
         * SET EXCEL BODY
         */
        $i++;
        $lp=1;
        foreach($serviceRepository->findByClientPointId($clientPoint->getId(),'ASC') as $value){
            //$value->getEndedAt()
            $activeWorksheet->setCellValue('A'.$i, $lp++);
            $activeWorksheet->setCellValue('B'.$i, $value->getEndedAt()->format('Y-m-d'));
            $activeWorksheet->setCellValue('C'.$i, $value->getTime());
            $activeWorksheet->setCellValue('D'.$i, $value->getDescription());
            $i++;
        }
        
        $writer = new Xlsx($spreadsheet);
        $writer->save($fileName);
        $fileContent = file_get_contents($fileName);
        unlink($fileName);
        return $fileContent;
    }
}