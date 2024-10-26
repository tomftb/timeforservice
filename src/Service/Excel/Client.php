<?php

namespace App\Service\Excel;

use App\Entity\Client as ClientEntity;

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
        $timeInH = 0;
        foreach($serviceRepository as $value){
            $timeInH = parent::getTimeInH(intval($value->getTime(),10));
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
            $this->activeWorksheet->setCellValue('D'.$this->row, $timeInH);
            /*
             * SUM TIME
             */
            $this->sumTime+=$timeInH;
            /*
             * SET LAST DATA SET ROW
             */
            $this->lastDataSetRow =  $this->row;
            /*
             * UPDATE ROW
             */
            $this->row++;
        }
        /*
         * SUM
         */
        parent::sumDataSetRow();
        /*
         * SET DEFAULTS
         */
        $this->firstDataSetRow = null;
        $this->lastDataSetRow = null;
        $this->sumTime = null;
    }

}