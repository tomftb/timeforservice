<?php
namespace App\Repository;

use App\Model\Client;
use Psr\Log\LoggerInterface;
/**
 * Description of ClientRepository
 *
 * @author Tomasz Borczynski
 */
class ClientRepository {
    public function __construct(
            private LoggerInterface $logger
    ){
        
    }
    public function findAll():array{
        $this->logger->info(__METHOD__);
        return [
                    new Client(
                        1,
                        'Farmline',
                        'Sieradzka',
                        'Toruń',
                        'working',
                    ),
                    new Client(
                        2,
                        'Taalo',
                        'Niedzialkowskiego',
                        'Wąbrzeźno',
                        'working'
                    ),
                    new Client(
                        3,
                        'Hospicjum Nadzieja',
                        'Niedzialkowskiego',
                        'Toruń',
                        'working'
                    ),
                ];
    }
    public function find(int $id):?Client{
        foreach(self::findAll() as $client){
            if($client->getId()===$id){
                return $client;
            }
        }
        return null;
    }
}
