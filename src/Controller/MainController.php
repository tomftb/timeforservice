<?php

namespace App\Controller;

use App\Repository\ClientRepository;
use App\Repository\ClientPointRepository;
use App\Repository\ServiceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Annotation\Route;

use Pagerfanta\Pagerfanta;
use Pagerfanta\Doctrine\ORM\QueryAdapter;

class MainController extends AbstractController
{
    #[Route('/', name: 'app_main_homepage')]
    public function homepage(
        ServiceRepository $serviceRepository,
        ClientRepository $clientRepository,
        ClientPointRepository $clientPointRepository,
        /* VARIABLE NAME MUST EQUAL URL PART ex. ?page => $page */
        #[MapQueryParameter] int $page = 1,
        #[MapQueryParameter] string $sort = 'endedAt',
        #[MapQueryParameter] string $sortDirection = 'ASC',
        #[MapQueryParameter] string $query = null,
        #[MapQueryParameter('clientsPoints', \FILTER_VALIDATE_INT)] array $searchClientsPoints = [],
    ): Response
    {
        $validSorts = ['description','clientPoint','user','endedAt'];
        $sort = in_array($sort,$validSorts) ? $sort : 'endedAt';
        $maxPerPage = 2;
        $pager = Pagerfanta::createForCurrentPageWithMaxPerPage(
                new QueryAdapter($serviceRepository->findBySearchWithClientPointQueryBuilder($query, $searchClientsPoints,$sort,$sortDirection),false),
               $page,
                $maxPerPage
        );
        return $this->render('main/homepage.html.twig', [
            'services' => $pager,
            'clients' => $clientRepository->findAll(),
            'searchClientsPoints' => $searchClientsPoints,
            'sort' => $sort,
            'sortDirection' => $sortDirection
        ]);
    }
}