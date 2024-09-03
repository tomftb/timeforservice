<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\ClientRepository;
use App\Repository\ServiceRepository;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;

class MainController extends AbstractController
{
    #[Route('/', name: 'app_main_homepage', methods: ['GET'])]
    public function homepage(ClientRepository $clientRepository,
            ServiceRepository $serviceRepository,
            #[MapQueryParameter('clients', \FILTER_VALIDATE_INT)] array $searchClients = [],
    ): Response
    {
        $clients = $clientRepository->findAll();
        $clientsCount = count($clients);
        $searchClients = [];
        $sortDirection = 'ASC';
        $validSorts = ['description','endedAt'];
        $sort = 'endedAt';
        $sort = in_array($sort,$validSorts) ? $sort : 'endedAt';
        $query = null;
        $page = 1;
        $pager = Pagerfanta::createForCurrentPageWithMaxPerPage(
        new QueryAdapter($serviceRepository->findBySearchQueryBuilder($query, $searchClients, $sort, $sortDirection)),
            $page,
            10
        );
        
        return $this->render('main/homepage.html.twig',[
            'clientCount'=>$clientsCount,
            'clients'=>$clients,
            'searchClients' => $searchClients,
            'sort' => $sort,
            'sortDirection' => $sortDirection,
            'services' => $pager
        ]);
    }
}
