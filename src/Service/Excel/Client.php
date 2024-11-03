<?php

namespace App\Service\Excel;

use App\Entity\Client as ClientEntity;
use App\Service\Excel\TimeSum;
use App\Service\Excel\RouteSum;
use App\Service\Excel\CostSum;
use App\Service\Excel\RouteCostSum;
use App\Service\Cooperation\Cost as CooperationCost;
use PhpOffice\PhpSpreadsheet\RichText\RichText;
use PhpOffice\PhpSpreadsheet\Style\Color;

class Client extends _Main
{

    private array $time=[];
    private array $route=[];
    private array $timeCost=[];
    private array $routeCost=[];
    private ?float $sumTimeCost=null;
    private ?float $sumRouteCost=null;
    private float $properRouteCost=0;
    private CooperationCost $cooperationCost;
    
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
        /*
         * INITIATE COOPERATION COST
         */
        $this->cooperationCost = new CooperationCost();
    }
    public function set(?ClientEntity $client,array $serviceRepository):void
    {
        if(is_null($client)){
            return;
        }
        /*
         * SET ROW TITLE - Client Point name
         */
        parent::setTitle($client->getName()." (".$client->getNin().")","A","E");
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
        $timeSum = new TimeSum(); 
        $distanceSum = new RouteSum();
        $costSum = new CostSum();
        $routeCostSum = new RouteCostSum();
        //$cooperationCost = new CooperationCost();
        foreach($serviceRepository as $value){
            /*
             * UPDATE TIME SUM
             */
            $timeSum->add($value->getRealTime());
            /*
             * UPDATE DISTANCE SUM
             */
            $distanceSum->add($value->getRoute());
            /*
             * UPDATE COST SUM
             */
            $costSum->add($value->getCost());
            /*
             * UPDATE ROUTE COST SUM
             */
            $routeCostSum->add($value->getRouteCost());
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
            $this->activeWorksheet->setCellValue('D'.$this->row, $value->getRealTime());
            $this->activeWorksheet->setCellValue('E'.$this->row, ($value->getRoute() > 0) ? strval($value->getRoute()) : '' );
            /*
             * SET LAST DATA SET ROW
             */
            $this->lastDataSetRow =  $this->row;
            /*
             * UPDATE ROW
             */
            /*
             * UPDATE COOPERATION COST
             */
            $this->cooperationCost->add(
                    $value->getCode(),
                    $value->getName(),
                    $value->getUnit(),
                    $value->getCost(),
                    $value->getRate(),
                    $value->getRealTime()
            );
            $this->row++;
        }
        array_push($this->time,$timeSum->get());
        array_push($this->route,$distanceSum->get());
        array_push($this->timeCost,$costSum->get());
        array_push($this->routeCost,$routeCostSum->get());
        parent::setSum($timeSum->get(),$distanceSum->get(),$costSum->get(),$routeCostSum->get());
       
        /*
         * SET DEFAULTS
         */
        $this->firstDataSetRow = null;
        $this->lastDataSetRow = null;
    }
    public function setWholeCost():void
    {
        if(empty($this->time)){
            return;
        }
        $this->row++;
        $richText = new RichText();
        $customRichText = $richText->createTextRun("PODSUMOWANIE:");
        $customRichText->getFont()->setBold(true);
        $customRichText->getFont()->setItalic(false);
        $customRichText->getFont()->setColor( new Color( \PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK));
        $this->spreadsheet->getActiveSheet()->getCell('C'.$this->row)->setValue($richText);
        $this->row++;
        /*
         * SET THE WHOLE COST
         */
        $this->activeWorksheet->setCellValue("C".$this->row,"Łączna suma:");
         /*
         * SET TIME AND DISTNACE COST SUM VALUE
         */
        $this->activeWorksheet->setCellValue("D".$this->row,array_sum($this->time));
        $this->activeWorksheet->setCellValue("E".$this->row,array_sum($this->route));
        $this->row++;
        /*
         * SET THE WHOLE COST
         */
        $this->activeWorksheet->setCellValue("C".$this->row,"Łączny koszt:");
        $this->sumTimeCost = array_sum($this->timeCost);
        $this->sumRouteCost = array_sum($this->routeCost);
        $this->activeWorksheet->setCellValue("D".$this->row,$this->sumTimeCost);
        $this->activeWorksheet->setCellValue("E".$this->row,$this->sumRouteCost);
        $this->row++;
        $this->activeWorksheet->setCellValue("C".$this->row,"Łącznie:");
        $this->activeWorksheet->setCellValue("D".$this->row,$this->sumTimeCost + $this->sumRouteCost);
        $this->row++;
    }
    public function setCooperation():void
    {
        if(empty($this->cooperationCost->get())){
            return;
        }
        $this->row++;
        $richText = new RichText();
        $customRichText = $richText->createTextRun("PKD:");
        $customRichText->getFont()->setBold(true);
        $customRichText->getFont()->setItalic(false);
        $customRichText->getFont()->setColor( new Color( \PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK));
        $this->spreadsheet->getActiveSheet()->getCell('C'.$this->row)->setValue($richText);
        $this->row++;
        $this->activeWorksheet->setCellValue("B".$this->row,'L.p.');
        $this->activeWorksheet->setCellValue("C".$this->row,'Nazwa usługi / towaru');
        $this->activeWorksheet->setCellValue("D".$this->row,'Jm');
        $this->activeWorksheet->setCellValue("E".$this->row,'Ilość');
        $this->activeWorksheet->setCellValue("F".$this->row,'Cena jednostkowa');
        $this->activeWorksheet->setCellValue("G".$this->row,'Wartość');
        $this->row++;
        $lp=1;
        $cost=0;
        foreach($this->cooperationCost->get() as $cooperation){
            $this->activeWorksheet->setCellValue("B".$this->row,strval($lp++).".");
            $this->activeWorksheet->setCellValue("C".$this->row,$cooperation->getName());
            $this->activeWorksheet->setCellValue("D".$this->row,$cooperation->getUnit());
            $this->activeWorksheet->setCellValue("E".$this->row,$cooperation->getRealTime());
            $this->activeWorksheet->setCellValue("F".$this->row,$cooperation->getRate());
            $this->activeWorksheet->setCellValue("G".$this->row,$cooperation->getCost());
            $this->row++;
            $cost+=$cooperation->getCost();
        }
        self::setCooperationMileage(
            $lp,
            '74.90.Z Pozostała działalność profesjonalna, naukowa i techniczna, gdzie indziej niesklasyfikowana',
            'g',
            90
        );
        $this->activeWorksheet->setCellValue("G".$this->row,$cost+$this->properRouteCost);
        $this->row++;
    }
    private function setCooperationMileage(int $lp=1,string $name='',string $unit='',float $rate=0):void
    {
        if($this->sumRouteCost === null || $this->sumRouteCost===0.0 || $this->sumRouteCost===0){
            return;
        }
        $routeCost = $this->sumRouteCost/$rate;
        $roundRouteCost = round($routeCost,2, PHP_ROUND_HALF_UP);
        $this->properRouteCost = $rate*$roundRouteCost;
        $this->activeWorksheet->setCellValue("B".$this->row,strval($lp++).".");
        $this->activeWorksheet->setCellValue("C".$this->row,$name);
        $this->activeWorksheet->setCellValue("D".$this->row,$unit);
        $this->activeWorksheet->setCellValue("E".$this->row,$roundRouteCost);
        $this->activeWorksheet->setCellValue("F".$this->row,$rate);
        $this->activeWorksheet->setCellValue("G".$this->row,$this->properRouteCost);
        $this->row++;
    }
}