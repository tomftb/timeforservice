<?php

namespace App\Service;

/**
 * Description of Error
 *
 * @author Tomasz Borczynski
 */
class Error {
    
    private bool $status=false;
    private string $message = "";
    private array $value = [];
    
    public function __construct(){

    }
    public function set(string $message=""):self{
        $this->status = true;
        $this->value[] = $message;
        return $this;
    }

    public function get():self{
        $this->message = implode(" , ",$this->value);
        return $this;
    }

    public function clear():self{
        $this->status = false;
        $this->message="";
        $this->value=[];
        return $this;
    }
    
    public function getStatus(){
        return $this->status;
    }
    
    public function getMessage(){
        return $this->message;
    }
}
