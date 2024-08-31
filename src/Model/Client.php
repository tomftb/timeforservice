<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace App\Model;

/**
 * Description of Client
 *
 * @author Tomasz Borczynski
 */
class Client {
    
    public function __construct(
        private int $id,
        private string $name,
        private string $street,
        private string $town,
        private string $status,  
    ){
        
    }
    public function getId(): int {
        return $this->id;
    }

    public function getName(): string {
        return $this->name;
    }

    public function getStreet(): string {
        return $this->street;
    }

    public function getTown(): string {
        return $this->town;
    }

    public function getStatus(): string {
        return $this->status;
    }


}
