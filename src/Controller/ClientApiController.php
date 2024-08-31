<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\ClientRepository;
/**
 * Description of ClientApiController
 *
 * @author Tomasz Borczynski
 */
 #[Route('/api/clients')]
class ClientApiController extends AbstractController{
    
    #[Route('',name: 'app_clientapi_getcollection',methods:['GET'])]
    public function getCollection(ClientRepository $clientRepository):Response
    {        
        return $this->json($clientRepository->findAll(),200);
    }
    #[Route('/{id<\d+>}',name: 'app_clientapi_get',methods:['GET'])]
    public function get(int $id,ClientRepository $clientRepository):Response
    {
        $client = $clientRepository->find($id);
        if(!$client){
            throw $this->createNotFoundException('Client not found');   
        }
        return $this->json($client,200);
    }
}
