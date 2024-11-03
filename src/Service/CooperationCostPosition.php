<?php

namespace App\Service\Excel;

/**
 * Description of TimeSum
 *
 * @author Tomasz Borczynski
 */
class DistanceSum {
    
    public function __construct(
        private float $sum = 0
    )
    {
    }
    public function add(?float $distance=null):void
    {
        if($distance!==null){
            $this->sum+=$distance;
        }
    }
    
    public function get():float
    {
        return $this->sum;
    }
}
