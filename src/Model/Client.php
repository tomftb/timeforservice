<?php

namespace App\Model;

use App\Model\ClientStatusEnum;

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
        private string $zipCode, 
        private string $nin, 
        private ClientStatusEnum $status,  
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

    public function getStatus(): ClientStatusEnum {
        return $this->status;
    }
    public function getStringStatus(): string {
        return $this->status->value;
    }
    public function getStatusImageFilename(): string {
        return match($this->status){
            ClientStatusEnum::NEW => 'images/NEW.png',
            ClientStatusEnum::TO_BE_SETTLED => 'images/TO_BE_SETTLED.png',
            ClientStatusEnum::SETTLED => 'images/SETTLED.png',
        };
    }
    public function getZipCode(): string {
        return $this->zipCode;
    }
    /*
     * NIN (National Insurance Number) jest brytyjskim odpowiednikiem polskiego NIP/PESEL.
     */
    public function getNin(): string {
        return $this->nin;
    }
}
