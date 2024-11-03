<?php

namespace App\Service\Excel;

/**
 * Description of Time
 *
 * @author Tomasz Borczynski
 */
class Distance {
    
    private ?float $distance;
    
    public function __construct(){ }
    public function add(?float $distance=null):void
    {
        if(is_null($distance)){
            return;
        }
        $this->distance = floatval($distance);
    }
    public function get():float|null
    {
        if(!isset($this->distance)){
            return null;
        }
        return $this->distance;
    }
}
