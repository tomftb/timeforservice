<?php

namespace App\Service\Excel;

use App\Entity\Client as ClientEntity;
use App\Service\Excel\Time;
use App\Service\Excel\TimeSum;
use App\Service\Excel\Distance;
use App\Service\Excel\DistanceSum;

class Client extends _Main
{

    private array $time=[];
    private array $distance=[];
    private array $timeCost=[];
    private array $distanceCost=[];
    
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
            'D'=>8,
            'E'=>10
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
        parent::setTitle($client->getName()." (".$client->getNin().")",['A','E']);
        /*
         * SET ROW OF COLUMNS DESCRIPTION 
         */
        parent::setDescription([
            'A'=>"Lp:",
            'B'=>"Data:",
            'C'=>"Punkt:",
            'D'=>"Czas(h):",
            'E'=>'Trasa(km):'
        ]);
        /*
         * SET ROWS WITH DATA
         */
        self::setData($serviceRepository);
    }
    private function setData(array $serviceRepository=[]):void
    {
        $lp=1;
        $time=[];
        $distance=[];
        $timeSum = new TimeSum(); 
        $distanceSum=new DistanceSum();
        foreach($serviceRepository as $k => $value){
            /*
             * SET TIME
             */
            $time[$k] = new Time(); 
            $time[$k]->add($value->getTime());
            $timeSum->add($time[$k]->get());
            /*
             * SET DISTANCE
             */
            $distance[$k] = new Distance();
            $distance[$k]->add($value->getRoute());
            $distanceSum->add($distance[$k]->get());       
            /*
             * SET FIRST DATA SET ROW
             */
            if($this->firstDataSetRow===null){
                $this->firstDataSetRow = $this->row;
            }
            $this->activeWorksheet->setCellValue('A'.$this->row, $lp++);
            $this->activeWorksheet->setCellValue('B'.$this->row, $value->getEndedAt()->format('Y-m-d'));
            $this->spreadsheet->getActiveSheet()->getCell('C'.$this->row)->setValue($value->getClientPoint()->getName()."\n".$value->getClientPoint()->getStreet()."\n".$value->getClientPoint()->getTown());
            $this->spreadsheet->getActiveSheet()->getStyle('C'.$this->row)->getAlignment()->setWrapText(true);
            $this->activeWorksheet->setCellValue('D'.$this->row, $time[$k]->get());
            $this->activeWorksheet->setCellValue('E'.$this->row, $distance[$k]->get());
            /*
             * SET LAST DATA SET ROW
             */
            $this->lastDataSetRow =  $this->row;
            /*
             * UPDATE ROW
             */
            $this->row++;
        }
        array_push($this->time,$timeSum->get());
        array_push($this->distance,$distanceSum->get());
        array_push($this->timeCost,$timeSum->get()*90);
        array_push($this->distanceCost,$distanceSum->get()*1.85);
        parent::setSum($timeSum->get(),$distanceSum->get(),end($this->timeCost),end($this->distanceCost));
       
        /*
         * SET DEFAULTS
         */
        $this->firstDataSetRow = null;
        $this->lastDataSetRow = null;
    }
    public function setWholeCost():void
    {
        $this->activeWorksheet->setCellValue("C".$this->row,"PODSUMOWANIE:");
        $this->row++;
        /*
         * SET THE WHOLE COST
         */
        $this->activeWorksheet->setCellValue("C".$this->row,"Łączna suma:");
         /*
         * SET TIME AND DISTNACE COST SUM VALUE
         */
        $this->activeWorksheet->setCellValue("D".$this->row,array_sum($this->time));
        $this->activeWorksheet->setCellValue("E".$this->row,array_sum($this->distance));
        $this->row++;
        /*
         * SET THE WHOLE COST
         */
        $this->activeWorksheet->setCellValue("C".$this->row,"Łączny koszt:");
        $sumCost = array_sum($this->timeCost);
        $sumDistance = array_sum($this->distanceCost);
        $this->activeWorksheet->setCellValue("D".$this->row,$sumCost);
        $this->activeWorksheet->setCellValue("E".$this->row,$sumDistance);
        $this->row++;
        $this->activeWorksheet->setCellValue("C".$this->row,"Łącznie:");
        $this->activeWorksheet->setCellValue("D".$this->row,$sumCost + $sumDistance);
        $this->row++;
    }
}