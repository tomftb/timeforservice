<?php

namespace App\Service\Excel;

/**
 * Description of Route
 *
 * @author Tomasz Borczynski
 */
class Route {
    
    private ?float $route;
    
    public function __construct(){ }
    public function add(?float $route=null):void
    {
        if(is_null($route)){
            return;
        }
        $this->route = floatval($route);
    }
    public function get():float|null
    {
        if(!isset($this->route)){
            return null;
        }
        return $this->route;
    }
}
