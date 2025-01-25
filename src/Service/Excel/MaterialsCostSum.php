<?php

namespace App\Service\Excel;

/**
 * Description of RouteCostSum
 *
 * @author Tomasz Borczynski
 */
class RouteCostSum {
    
    public function __construct(
        private float $sum = 0
    )
    {
    }
    public function add(?float $cost=null):void
    {
        if($cost!==null){
            $this->sum+=$cost;
        }
    }
    
    public function get():float
    {
        return $this->sum;
    }
}
