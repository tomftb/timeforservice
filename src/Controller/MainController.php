<?php

namespace App\Controller;

use App\Repository\ClientRepository;
use App\Repository\ServiceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'app_main_homepage')]
    public function homepage(
        ServiceRepository $serviceRepository,
        ClientRepository $clientRepository,
        #[MapQueryParameter('query')] string $query = null,
        #[MapQueryParameter('clients', \FILTER_VALIDATE_INT)] array $searchClients = [],
    ): Response
    {

        $services = $serviceRepository->findBySearch($query, $searchClients);

        return $this->render('main/homepage.html.twig', [
            'services' => $services,
            'clients' => $clientRepository->findAll(),
            'searchClients' => $searchClients,
        ]);
    }
}