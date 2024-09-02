<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\ClientRepository;
use App\Entity\Client;
use Doctrine\ORM\EntityManagerInterface;
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
    #[Route('/new', name: 'app_client_new', methods: ['GET'])]
    public function new(EntityManagerInterface $entityManager): Response
    {
        
        $client = new Client();
        $client->setName('Farmline Sp. z o.o.');
        $client->setStreet('Paderewskiego 3');
        $client->setZipCode('87-162');
        $client->setTown('Lubicz Górny');
        $client->setNin('879-264-70-97');
        $client->setStatus('NEW');
        
        $entityManager->persist($client);
        $entityManager->flush();
        //echo "<pre>";
        //var_dump($client);
        //echo "</pre>";
        //dd('new clinet');
        return new Response(sprintf(
                "Client created - %d",
                $client->getId()
        ));
    }
}
