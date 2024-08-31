<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Description of ClientApiController
 *
 * @author Tomasz Borczynski
 */
class ClientApiController extends AbstractController{
    
    #[Route('/api/clients','name: api_all_clients',methods:['GET'])]
    public function getCollection():Response
    {
        $clients = [
            [
                'id'=>1,
                'name'=>'Farmline',
                'street'=>'Sieradzka',
                'town'=>'Toruń',
                'status'=>'working',
            ],
            [
                'id'=>2,
                'name'=>'Taalo',
                'street'=>'Niedzialkowskiego',
                'town'=>'Wąbrzeźno',
                'status'=>'working'
            ],
            [
                'id'=>2,
                'name'=>'Hospicjum Nadzieja',
                'street'=>'Niedzialkowskiego',
                'town'=>'Toruń',
                'status'=>'working'
            ],
        ];
        return $this->json($clients,200);
    }
    #[Route('/api/client/{id}','name: api_client',methods:['GET'])]
    public function get():Response
    {
        
    }
}
