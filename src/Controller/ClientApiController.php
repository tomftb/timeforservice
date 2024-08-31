<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Model\Client;
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
                2,
                'Hospicjum Nadzieja',
                'Niedzialkowskiego',
                'Toruń',
                'working'
            ),
        ];
        return $this->json($clients,200);
    }
    #[Route('/api/client/{id}','name: api_client',methods:['GET'])]
    public function get():Response
    {
        
    }
}
