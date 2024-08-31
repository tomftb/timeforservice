<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\ClientRepository;

class MainConotroller extends AbstractController
{
    #[Route('/', name: 'app_main_home', methods: ['GET'])]
    public function homepage(ClientRepository $clientRepository): Response
    {
        $clients = $clientRepository->findAll();
        $clientsCount = count($clients);
        
        return $this->render('main/homepage.html.twig',[
            'clientCount'=>$clientsCount,
            'clients'=>$clients
        ]);
    }
}
