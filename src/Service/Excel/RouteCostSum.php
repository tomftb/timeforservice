<?php

namespace App\Service\Excel;

/**
 * Description of TimeSum
 *
 * @author Tomasz Borczynski
 */
class TimeSum {
    
    public function __construct(
        private float $sum = 0
    )
    {
    }
    public function add(?float $time=null):void
    {
        if($time!==null){
            $this->sum+=$time;
        }
    }
    
    public function get():float
    {
        return $this->sum;
    }
}
