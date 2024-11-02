<?php

namespace App\Service\Excel;

use App\Entity\ClientPoint as ClientPointEntity;

class ClientPoint extends _Main
{ 
    public function __construct(
        private string $appTmp
        )
    {
        /*
         * CALL PARENT CONSTRUCT
         */
        parent::__construct($appTmp);
        /*
         * SET COLUMNS DIMENSIONS
         */
        parent::setColumnsDimensions([
            'A'=>5,
            'B'=>10,
            'C'=>8,
            'D'=>30
        ]);
    }
    public function set(?ClientPointEntity $clientPoint,array $serviceRepository):void
    {
        if(is_null($clientPoint)){
            //throw new Exception('Client Point data missing');
            return;
        }
        /*
         * SET ROW TITLE - Client Point name
         */
        parent::setTitle($clientPoint->getName(),'A','D');
        /*
         * SET ROW OF COLUMNS DESCRIPTION 
         */
        parent::setDescription([
            'A'=>"Lp:",
            'B'=>"Data:",
            'C'=>"Czas(h):",
            'D'=>"Opis:"
        ]);
        /*
         * SET ROWS WITH DATA
         */
        self::setData($serviceRepository); 
    }
    private function setData(array $serviceRepository=[]):void
    {
        $lp=1;
        foreach($serviceRepository as $value){
            $this->activeWorksheet->setCellValue('A'.$this->row, $lp++);
            $this->activeWorksheet->setCellValue('B'.$this->row, $value->getEndedAt()->format('Y-m-d'));
            $this->activeWorksheet->setCellValue('C'.$this->row, $value->getTime());
            $this->activeWorksheet->setCellValue('D'.$this->row, $value->getDescription());
            $this->row++;
        }
    }
}