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
                        'Farmline Sp. z o.o.',
                        'Paderewskiego 3',
                        'Lubicz Górny',
                        '87-162',
                        '879-264-70-97',
                        'working',
                    ),
                    new Client(
                        2,
                        'Farmline II Sp. z o.o.',
                        'Paderewskiego 3',
                        'Lubicz Górny',
                        '87-162',
                        '701-039-21-23',
                        'working',
                    ),
                    new Client(
                        3,
                        'Pulsline Sp. z o.o.',
                        'Paderewskiego 3',
                        'Lubicz Górny',
                        '87-162',
                        '701-039-21-52',
                        'working',
                    ),
                    new Client(
                        4,
                        'Farmpuls Sp. z o.o.',
                        'Paderewskiego 3',
                        'Lubicz Górny',
                        '87-162',
                        '879-267-79-51',
                        'working',
                    ),
                    new Client(
                        5,
                        'Puls Ludmiła Baranowska',
                        'Paderewskiego 3',
                        'Lubicz Górny',
                        '87-162',
                        '879-026-09-45',
                        'working',
                    ),
                    new Client(
                        6,
                        'TAALO SP. Z O.O.',
                        'Wolności 10',
                        'Wąbrzeźno',
                        '87-200',
                        '878-179-86-39',
                        'working',
                    ),
                    new Client(
                        7,
                        'FUNDACJA SPOŁECZNO-CHARYTATYWNA POMOC RODZINIE I ZIEMI',
                        'WŁOCŁAWSKA 169 B',
                        'Toruń',
                        '87-100',
                        '9561603640',
                        'working',
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
