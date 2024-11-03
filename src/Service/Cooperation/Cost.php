<?php

namespace App\Service;

use App\Service\CooperationPosition;
use App\Service\CooperationCostPosition;

/**
 * Description of CooperationCost
 *
 * @author Tomasz Borczynski
 */
class CooperationCost {
    private array $cooperation=[];
    
    public function __construct()
    {
    }
    public function add(?string $code=null,?string $name=null,?string $unit=null, ?float $cost=null, ?float $rate=null, ?float $realTime=null):void
    {
        $cooperationPosition = new CooperationPosition();
        $cooperationPosition->add(
            $code,
            $name,
            $unit,
            $cost,
            $rate,
            $realTime
        );
        self::set($cooperationPosition->get());
    }
    private function set(CooperationPosition $cooperationPosition):void
    {
        $idx = $cooperationPosition->getIdx();
        if(array_key_exists($cooperationPosition->getIdx(), $this->cooperation)){
            $this->cooperation[$idx]->setCost($cooperationPosition->getCost());   
            $this->cooperation[$idx]->setRealTime($cooperationPosition->getRealTime());   
        }
        else{
            $this->cooperation[$idx]=new CooperationCostPosition();
            $this->cooperation[$idx]->setName($cooperationPosition->getCode()." ".$cooperationPosition->getName());
            $this->cooperation[$idx]->setUnit($cooperationPosition->getUnit());
            $this->cooperation[$idx]->setCost($cooperationPosition->getCost());
            $this->cooperation[$idx]->setRate($cooperationPosition->getRate());
            $this->cooperation[$idx]->setRealTime($cooperationPosition->getRealTime());
        }
    }
    public function get():array
    {
        //dd($this->cooperation);
        return $this->cooperation;
    }
}
