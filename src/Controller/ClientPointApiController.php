<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\ClientPoint;
use App\Repository\ServiceRepository;
use App\Service\Excel\ClientPoint as ClientPointExcel;
use App\Service\Excel\Api;

/**
 * Description of ClientPointApiController
 *
 * @author Tomasz Borczynski
 */
 #[Route('/api/clientpoint')]
class ClientPointApiController extends AbstractController {
    
    use Api;
     
    #[Route('/{id}/export_services', name: 'app_clientpoint_export_services', methods: ['GET'])]
    public function exportServices(ClientPoint $clientPoint,ServiceRepository $serviceRepository,ClientPointExcel $clientPointExcel): Response
    {
        $clientPointExcel->set($clientPoint,$serviceRepository->findByClientPointId($clientPoint->getId(),'ASC'));
        return $this->returnExcel($clientPoint->getName(),$clientPointExcel->get()); 
    }
}
