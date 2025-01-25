<?php

namespace App\Service\Excel;

use App\Entity\Client as ClientEntity;
use App\Service\Excel\TimeSum;
use App\Service\Excel\RouteSum;
use App\Service\Excel\CostSum;
use App\Service\Excel\RouteCostSum;
use App\Service\Excel\MaterialsCostSum;
use App\Service\Cooperation\Cost as CooperationCost;
use PhpOffice\PhpSpreadsheet\RichText\RichText;
use PhpOffice\PhpSpreadsheet\Style\Color;

class Client extends _Main
{

    private array $time=[];
    private array $route=[];
    private array $timeCost=[];
    private array $routeCost=[];
    private array $materialsCost=[];
    private ?float $sumTimeCost=null;
    private ?float $sumMaterialsCost=null;

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
            'E'=>10,
            'F'=>16,
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
            'E'=>'Trasa(km):',
            'F'=>'Materiały(zł):'
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
        $materialsCostSum = new MaterialsCostSum();
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
             * UPDATE MATERIALS COST SUM
             */
            $materialsCostSum->add($value->getMaterialCosts());
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
            $this->activeWorksheet->setCellValue('F'.$this->row, ($value->getMaterialCosts() > 0 && $value->getMaterialCosts()!==null) ? strval($value->getMaterialCosts()) : '');
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
        array_push($this->materialsCost,$materialsCostSum->get());
        parent::setSum($timeSum->get(),$distanceSum->get(),$costSum->get(),$routeCostSum->get(),$materialsCostSum->get());
       
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
        $this->sumMaterialsCost = array_sum($this->materialsCost);
         /*
         * SET TIME, DISTNACE AND MATERIALS COST SUM VALUE
         */
        $this->activeWorksheet->setCellValue("D".$this->row,array_sum($this->time));
        $this->activeWorksheet->setCellValue("E".$this->row,array_sum($this->route));
        $this->activeWorksheet->setCellValue("F".$this->row,$this->sumMaterialsCost);
        $this->row++;
        /*
         * SET THE WHOLE COST
         */
        $this->activeWorksheet->setCellValue("C".$this->row,"Łączny koszt:");
        $this->sumTimeCost = array_sum($this->timeCost);
        $this->mileage->setSumRouteCost(array_sum($this->routeCost));
        $this->activeWorksheet->setCellValue("D".$this->row,$this->sumTimeCost);
        $this->activeWorksheet->setCellValue("E".$this->row,$this->mileage->getSumRouteCost());
        $this->activeWorksheet->setCellValue("F".$this->row,$this->sumMaterialsCost);
        $this->row++;
        $this->activeWorksheet->setCellValue("C".$this->row,"Łącznie:");
        $this->activeWorksheet->setCellValue("D".$this->row,$this->sumTimeCost + $this->mileage->getSumRouteCost() + $this->sumMaterialsCost);
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
        self::setCooperationMileageAndMaterials(
            $lp,
            '74.90.Z Pozostała działalność profesjonalna, naukowa i techniczna, gdzie indziej niesklasyfikowana',
            'g',
            80
        );
        $this->activeWorksheet->setCellValue("G".$this->row,$cost+$this->properRouteCost+$this->sumMaterialsCost);
        $this->row++;
    }

    private function setCooperationMileageAndMaterials(int $lp=1):void
    {
        //if($this->mileage->getSumRouteCost()===0.0 || $this->mileage->getSumRouteCost()===0){
        //    return;
       // }
        $this->mileage->setRouteCost();
        $this->mileage->setRoundRouteCost();
        $this->properRouteCost = $this->mileage->getProperRouteCost();
        $this->activeWorksheet->setCellValue("B".$this->row,strval($lp++).".");
        $this->activeWorksheet->setCellValue("C".$this->row,$this->mileage->getName());
        $this->activeWorksheet->setCellValue("D".$this->row,$this->mileage->getUnit());
        $this->activeWorksheet->setCellValue("E".$this->row,$this->mileage->getRoundRouteCost());
        $this->activeWorksheet->setCellValue("F".$this->row,$this->mileage->getRate());
        $this->activeWorksheet->setCellValue("G".$this->row,$this->mileage->getProperRouteCost()+$this->sumMaterialsCost);
        $this->row++;
    }
}