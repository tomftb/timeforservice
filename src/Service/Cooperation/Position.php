<?php

namespace App\Service\Cooperation;

/**
 * Description of Position
 *
 * @author Tomasz Borczynski
 */
class Position {
    
    private string $code='no_code';
    private string $name='no_name';
    private string $unit='no_unit';
    private float $cost=0;
    private string $rate='no_rate';
    private float $realTime=0;
    private string $idx='no_code-no_name-no_unit';
     
    public function __construct( )
    {
    }
    public function add(?string $code=null,?string $name=null,?string $unit=null, ?float $cost=null, ?float $rate=null, ?float $realTime=null):void
    {
        $this->code = ($code !== null ) ? $code : 'no_code';
        $this->name = ($name !== null ) ? $name : 'no_name';
        $this->unit = ($unit !== null ) ? $unit : 'no_unit';
        $this->cost = ($cost !== null ) ? $cost : 0;
        $this->rate = ($rate !== null ) ? $rate : 'no_rate';
        $this->realTime = ($realTime !== null ) ? $realTime : 0;
        $this->idx = preg_replace('/\s+/', '_',$this->code.'-'.$this->name.'-'.$this->unit."-".strval($this->rate));
    }
    public function getIdx():string
    {
        return $this->idx;
    }
    public function getCode():string
    {
        return $this->code;
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
