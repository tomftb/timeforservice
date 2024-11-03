<?php

namespace App\Service\Cooperation;

/**
 * Description of CostPosition
 *
 * @author Tomasz Borczynski
 */
class CostPosition {

    public function __construct(
        private string $name='no_name',
        private string $unit='no_unit', 
        private float $rate=0, 
        private float $realTime=0, 
        private float $cost=0
    )
    {
    }
    public function setName(string $name='no_name'):void
    {
        $this->name=$name;
    }
    public function setUnit(string $unit='no_unit'):void
    {
        $this->unit=$unit;
    }
    public function setCost(float $cost=0):void
    {
        $this->cost+=$cost;
    }
    public function setRate(float $rate=0):void
    {
        $this->rate=$rate;
    }
    public function setRealTime(float $realTime=0):void
    {
        $this->realTime+=$realTime;
    }
    public function getName():string
    {
        return $this->name;
    }
    public function getUnit():string
    {
        return $this->unit;
    }
    public function getCost():float
    {
        return $this->cost;
    }
    public function getRate():float
    {
        return $this->rate;
    }
    public function getRealTime():float
    {
        return $this->realTime;
    }
    public function get():self
    {
        return $this;
    }
}
