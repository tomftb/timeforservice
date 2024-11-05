<?php

namespace App\Service\Cooperation;

/**
 * Description of Mileage
 *
 * @author Tomasz Borczynski
 */
class Mileage {
    public function __construct(
        private string $name='',
        private string $unit='',
        private float $rate=0,
        private float $sumRouteCost=0,
        private float $routeCost=0,
        private float $roundRouteCost=0,
    )
    {
        
    }
    public function setName(string $name=''):Mileage
    {
        $this->name=$name;
        return $this;
    }
    public function setUnit(string $unit=''):Mileage
    {
        $this->unit=$unit;
        return $this;
    }
    public function setRate(float $rate=0):Mileage
    {
        $this->rate=$rate;
        return $this;
    }
    public function getName():string
    {
        return $this->name;
    }
    public function getUnit():string
    {
        return $this->unit;
    }
    public function getRate(float $rate=0):float
    {   
        return $this->rate;
    }
    public function setSumRouteCost(float $sumRouteCost=0):Mileage
    {
        $this->sumRouteCost = $sumRouteCost;
        return $this;
    }
    public function getSumRouteCost():float
    {
        return $this->sumRouteCost;
    }
    public function setRouteCost():Mileage
    {
        if($this->rate === 0 || $this->rate === 0.0){
            return $this;
        }
        $this->routeCost = $this->sumRouteCost/$this->rate;
        return $this;
    }
    public function setRoundRouteCost():Mileage
    {
        $this->roundRouteCost = round($this->routeCost,2, PHP_ROUND_HALF_UP);
        return $this;
    }
    public function getRoundRouteCost():float
    {
        return $this->roundRouteCost;
    }
    public function getProperRouteCost():float
    {
        return $this->rate*$this->roundRouteCost;
    }
}
