<?php

namespace App\Service\Excel;

/**
 * Description of RouteSum
 *
 * @author Tomasz Borczynski
 */
class RouteSum {
    
    public function __construct(
        private float $sum = 0
    )
    {
    }
    public function add(?float $route=null):void
    {
        if($route!==null){
            $this->sum+=$route;
        }
    }
    
    public function get():float
    {
        return $this->sum;
    }
}
