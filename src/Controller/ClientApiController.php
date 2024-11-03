<?php

namespace App\Controller;

use App\Entity\Client;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\ClientRepository;
use App\Repository\ServiceRepository;
use App\Service\Excel\Client as ClientExcel;
use Symfony\Component\HttpFoundation\Request;
use App\Service\Excel\Api;
/**
 * Description of ClientApiController
 *
 * @author Tomasz Borczynski
 */
 #[Route('/api/client')]
class ClientApiController extends AbstractController{
    
    use Api;
     
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
        /*
     * ORDER IS IMPORTANT
     */
    #[Route('/multi_export_services', name: 'app_client_multi_export_services', methods: ['GET'])]
    public function multiExportServices(Request $request,ClientRepository $clientRepository,ServiceRepository $serviceRepository,ClientExcel $clientExcel): Response
    {
        /*
         * SET QUERY TO EXECUTE
         */
        $findBy = 'findByClientId';
        /*
         * SET STARTING VARIABLES
         */
        $endedFrom = null;
        $endedTo = null;
        $fileLabel = '';
        /*
         * CHECK DATE FROM
         */
        if(!is_null($request->query->get('endedfrom'))){
            $endedFrom = str_replace("T"," ",$request->query->get('endedfrom'));
            $findBy = 'findByClientIdWithEndedFrom';
            $fileLabel = 'from '.str_replace(":","_",$endedFrom);
        }
        /*
         * CHECK DATE TO
         */
        if(!is_null($request->query->get('endedto'))){
            $endedTo = str_replace("T"," ",$request->query->get('endedto'));
            $findBy = 'findByClientIdWithEndedTo';
            $fileLabel = 'to '.str_replace(":","_",$endedTo);
        }
        /*
         * CHECK DATE FROM AND TO
         */
        if($endedFrom!==null && $endedTo!==null){
            $findBy = 'findByClientIdWithEndedIn';
            $fileLabel = 'from '.$endedFrom.' to '.$endedTo;
        }
        /*
         * SET CLIENTS IDS
         */
        $clientIds = explode(",",$request->query->get('id'));
        $clients = $clientRepository->findByIds($clientIds);
        /*
         * TO DO NAME OF FILE
         */
        $fileName = 'all (services '.$fileLabel.').xlsx';
        foreach($clients as $client){
            $clientExcel->setRate($client->getHourlyRate());
            $clientExcel->setMileage($client->getKilometerRate());
            $clientExcel->set($client,$serviceRepository->{$findBy}($client->getId(),'ASC',$endedFrom,$endedTo));
        }
        $clientExcel->setWholeCost();
        $clientExcel->setCooperation();
        return $this->returnExcel($fileName,$clientExcel->get()); 
    }
    #[Route('/{id}/export_services', name: 'app_client_export_services', methods: ['GET'])]
    public function exportServices(Client $client,ServiceRepository $serviceRepository,ClientExcel $clientExcel): Response
    {
        $clientExcel->setRate($client->getHourlyRate());
        $clientExcel->setMileage($client->getKilometerRate());
        $clientExcel->set($client,$serviceRepository->findByClientId($client->getId(),'ASC'));
        return $this->returnExcel($client->getName(),$clientExcel->get()); 
    }
}
