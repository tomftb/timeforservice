<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\ClientRepository;
/**
 * Description of ClientController
 *
 * @author Tomasz Borczynski
 */
#[Route('/client')]
class ClientController extends AbstractController{
    
    #[Route('/{id<\d+>}', name: 'app_client_show', methods: ['GET'])]
    public function show(int $id,ClientRepository $clientRepository): Response
    {
        $client = $clientRepository->find($id);
        if(!$client){
            throw $this->createNotFoundException('Client not found');   
        }
        
        
        return $this->render('client/show.html.twig',[
            'client'=>$client
        ]);
    }
}
