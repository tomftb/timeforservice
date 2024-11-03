<?php

namespace App\Service\Cooperation;

use App\Service\Cooperation\Position;
use App\Service\Cooperation\CostPosition;

/**
 * Description of Cost
 *
 * @author Tomasz Borczynski
 */
class Cost {
    private array $cooperation=[];
    
    public function __construct()
    {
    }
    public function add(?string $code=null,?string $name=null,?string $unit=null, ?float $cost=null, ?float $rate=null, ?float $realTime=null):void
    {
        $cooperationPosition = new Position();
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
    private function set(Position $cooperationPosition):void
    {
        $idx = $cooperationPosition->getIdx();
        if(array_key_exists($cooperationPosition->getIdx(), $this->cooperation)){
            $this->cooperation[$idx]->setCost($cooperationPosition->getCost());   
            $this->cooperation[$idx]->setRealTime($cooperationPosition->getRealTime());   
        }
        else{
            $this->cooperation[$idx]=new CostPosition();
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
