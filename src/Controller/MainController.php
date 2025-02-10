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
    #[Route('/', name: 'app_main')]
    public function main(): Response
    {
        //return $this->render('main/home.html.twig');
        return $this->redirectToRoute('app_login', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/home', name: 'app_main_homepage')]
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
        //dd($this->getUser()->getId());
        // BAD - $user->getRoles() will not know about the role hierarchy
        //$hasAccess = in_array('ROLE_ADMIN', $user->getRoles());

        // GOOD - use of the normal security methods
        //$hasAccess = $this->isGranted('ROLE_ADMIN');
        //$this->denyAccessUnlessGranted('ROLE_ADMIN');
        //dd($searchClientsPoints);
        $validSorts = ['description','clientPoint','user','endedAt'];
        $sort = in_array($sort,$validSorts) ? $sort : 'endedAt';
        $maxPerPage = 10;
        $pager = Pagerfanta::createForCurrentPageWithMaxPerPage(
                new QueryAdapter($serviceRepository->findBySearchWithClientPointQueryBuilder($query, $searchClientsPoints,$sort,$sortDirection),false),
                $page,
                $maxPerPage
        );
        return $this->render('main/homepage.html.twig', [
            'services' => $pager,
            'clientsPoints'=>$clientPointRepository->findAll(),
            'clients' => $clientRepository->findAll(),
            'searchClientsPoints' => $searchClientsPoints,
            'sort' => $sort,
            'sortDirection' => $sortDirection
        ]);
    }
}