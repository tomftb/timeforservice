<?php

namespace App\Service\Excel;

use App\Entity\Client as ClientEntity;
use App\Service\Excel\Time;
use App\Service\Excel\TimeSum;

class Client extends _Main
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
        self::setColumnsDimensions([
            'A'=>5,
            'B'=>11,
            'C'=>45,
            'D'=>8
        ]);
    }
    public function set(?ClientEntity $client,array $serviceRepository):void
    {
        if(is_null($client)){
            return;
        }
        /*
         * SET ROW TITLE - Client Point name
         */
        parent::setTitle($client->getName()." (".$client->getNin().")",['A','D']);
        /*
         * SET ROW OF COLUMNS DESCRIPTION 
         */
        parent::setDescription([
            'A'=>"Lp:",
            'B'=>"Data:",
            'C'=>"Punkt:",
            'D'=>"Czas(h):"
        ]);
        /*
         * SET DATA SET SUM COLUMN
         */
        parent::setDataSumColumnLabel('E',"Suma(h):");
        /*
         * SET DATA SET SUM COLUMN
         */
        parent::setDataSumColumn('F');
        /*
         * SET DATA SET COLUMN TO SUM
         */
        parent::setDataColumnToSum('D');
        /*
         * SET ROWS WITH DATA
         */
        self::setData($serviceRepository);
    }
    private function setData(array $serviceRepository=[]):void
    {
        $lp=1;
        $time=[];
        $sum = new TimeSum(); 
        foreach($serviceRepository as $k => $value){
            $time[$k] = new Time(); 
            $time[$k]->add($value->getTime());
            $sum->add($time[$k]->get());
            /*
             * SET FIRST DATA SET ROW
             */
            if($this->firstDataSetRow===null){
                $this->firstDataSetRow = $this->row;
            }
            $this->activeWorksheet->setCellValue('A'.$this->row, $lp++);
            $this->activeWorksheet->setCellValue('B'.$this->row, $value->getEndedAt()->format('Y-m-d'));
            $this->spreadsheet->getActiveSheet()->getCell('C'.$this->row)->setValue($value->getClientPoint()->getName()."\n".$value->getClientPoint()->getStreet().", ".$value->getClientPoint()->getTown());
            $this->spreadsheet->getActiveSheet()->getStyle('C'.$this->row)->getAlignment()->setWrapText(true);
            $this->activeWorksheet->setCellValue('D'.$this->row, $time[$k]->get());
            /*
             * SET LAST DATA SET ROW
             */
            $this->lastDataSetRow =  $this->row;
            /*
             * UPDATE ROW
             */
            $this->row++;
        }       
        parent::sumDataSetRow($sum->get());
        /*
         * SET DEFAULTS
         */
        $this->firstDataSetRow = null;
        $this->lastDataSetRow = null;
        $this->sumTime = null;
    }

}