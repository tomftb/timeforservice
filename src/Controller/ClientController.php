<?php

namespace App\Controller;

use App\Entity\Client;
use App\Form\ClientType;
use App\Repository\ClientRepository;
use App\Repository\ServiceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\HeaderUtils;
use App\Service\Excel\Client as ClientExcel;
use function symfony\component\string\u;

/**
 * Description of ClientController
 *
 * @author Tomasz Borczynski
 */
#[Route('/client')]
class ClientController extends AbstractController{
    
   #[Route('/', name: 'app_client_index', methods: ['GET'])]
    public function index(ClientRepository $clientRepository): Response
    {
        return $this->render('client/index.html.twig', [
            'clients' => $clientRepository->findAll(),
        ]);
    }
    #[Route('/new', name: 'app_client_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $client = new Client();
        $form = $this->createClientForm($client);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($client);
            $entityManager->flush();
            $this->addFlash('success', 'Client created');
            /*
             * CHECK REQUEST HEADER FOR OPEN PROPER WINDOW - MODAL OR NEW FULL PAGE
             */
            if($request->headers->has('turbo-frame')){
                $stream = $this->renderBlockView('client/new.html.twig','stream_success',[
                    'client' => $client
                ]);
                $this->addFlash('stream',$stream);
            }
            return $this->redirectToRoute('app_client_index', [], Response::HTTP_SEE_OTHER);
        }   
        return $this->render('client/new.html.twig', [
            'client' => $client,
            'form' => $form,
        ]);
    }
    #[Route('/multi_services', name: 'app_client_multi_services', methods: ['GET','POST'])]
    public function multiServices(Request $request, ClientRepository $clientRepository): Response
    {
        $clients = $clientRepository->all();
        return $this->render('client/optionServices.html.twig', [
            'clients' => $clients,
        ]);
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
            $clientExcel->set($client,$serviceRepository->{$findBy}($client->getId(),'ASC',$endedFrom,$endedTo));
        }
        $fileContent = $clientExcel->get();
        $response = new Response($fileContent);
        $response->headers->set('Content-Type', 'application/vnd.ms-excel');
        $response->headers->set('max-age', '0');
        $disposition = HeaderUtils::makeDisposition(
            HeaderUtils::DISPOSITION_ATTACHMENT,
            $fileName,
            u($fileName)->ascii()   
        );
        $response->headers->set('Content-Disposition', $disposition);
        return $response;
    }
    #[Route('/{id}', name: 'app_client_show', methods: ['GET'])]
    public function show(Client $client): Response
    {
        return $this->render('client/show.html.twig', [
            'client' => $client,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_client_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Client $client, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createClientForm($client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Client updated');
            /*
             * CHECK REQUEST HEADER
             */
            if($request->headers->has('turbo-frame')){
                $stream = $this->renderBlockView('client/edit.html.twig','stream_success',[
                    'client' => $client
                ]);
                $this->addFlash('stream',$stream);
            }
            return $this->redirectToRoute('app_client_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('client/edit.html.twig', [
            'client' => $client,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_client_delete', methods: ['POST'])]
    public function delete(Request $request, Client $client, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$client->getId(), $request->request->get('_token'))) {
            $id = $client->getId();
            $entityManager->remove($client);
            $entityManager->flush();

            $this->addFlash('success', 'Client deleted');
            /*
             * ADD CHECK HEADER FOR MODAL
             */
            if($request->headers->has('turbo-frame')){
                $stream = $this->renderBlockView('client/delete.html.twig','stream_success',[
                    'id' => $id
                ]);
                $this->addFlash('stream',$stream);
            }
        }

        return $this->redirectToRoute('app_client_index', [], Response::HTTP_SEE_OTHER);
    }
    /*
     * Custom Client Form
     */
    private function createClientForm(Client $client=null ): FormInterface
    {
        $client=$client ?? new Client();
        return $this->createForm(ClientType::class,$client ,[
            'action' => $client->getId() ? $this->generateUrl('app_client_edit',['id'=>$client->getId()]) : $this->generateUrl( 'app_client_new' ), 
        ]);
    }
    #[Route('/{id}/services', name: 'app_client_services', methods: ['GET'])]
    public function services(Client $client,ServiceRepository $serviceRepository): Response
    {
        return $this->render('client/services.html.twig', [
            'client' => $client,
            'services' => $serviceRepository->findByClientId($client->getId(),'DESC')
        ]);
    }
    #[Route('/{id}/export_services', name: 'app_client_export_services', methods: ['GET'])]
    public function exportServices(Client $client,ServiceRepository $serviceRepository,ClientExcel $clientExcel): Response
    {
        $clientExcel->set($client,$serviceRepository->findByClientId($client->getId(),'ASC'));
        $fileContent = $clientExcel->get();
        $response = new Response($fileContent);
        $response->headers->set('Content-Type', 'application/vnd.ms-excel');
        $response->headers->set('max-age', '0');
        $disposition = HeaderUtils::makeDisposition(
            HeaderUtils::DISPOSITION_ATTACHMENT,
            $client->getName().' (services).xlsx',
            u($client->getName())->ascii()   
        );
        $response->headers->set('Content-Disposition', $disposition);
        return $response;
    }
}
