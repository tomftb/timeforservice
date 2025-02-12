<?php

namespace App\Service;

/**
 * Description of Time
 *
 * @author Tomasz Borczynski
 */
class ConvertTime {
    
    private float $time;
    private float $modulo=0;
    private float $half=0;
    private int $hour=0;
    
    public function __construct(){ }
    public function add(int|string $time=0):void
    {
        $this->time = intval($time,10);
        self::convert();
    }
    private function convert()
    {
        //echo "start time -> ".$this->time;
        if($this->time === 0){
            return 0;
        }
        self::setModulo();
        self::setWhole();
        self::setHalf();
        $this->time = $this->modulo+$this->hour+$this->half;
    }
    /*
     * SET MODULO
     */
    private function setModulo():void
    { 
        $modulo = $this->time % 30;
        if($modulo>0){
            $this->modulo = 0.5;
            $this->time = $this->time - $modulo;
        }
    }
    /*
     * SET DIVIDED = 60
     */
    private function setWhole():void
    {
        /*
         * GET HOUR
         */
        $this->hour = $this->time / 60;
        if($this->hour>0){
            $this->time = $this->time - ($this->hour * 60);
        }
    }
    private function setHalf():void
    {
        /*
         * GET HALF AN HOUR
         */
        $half = $this->time / 30;
        if($half>0){
            $this->half = 0.5;
        }
    }
    public function get():float
    {
        return $this->time;
    }
}
