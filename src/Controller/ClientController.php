<?php

namespace App\Controller;

use App\Entity\Client;
use App\Form\ClientType;
use App\Form\ClientDeleteType;
use App\Repository\ClientRepository;
use App\Repository\ServiceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Form\FormInterface;
use App\Model\YesOrNoEnum;

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
            'clients' => $clientRepository->findAllDesc(),
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
    public function multiServices(ClientRepository $clientRepository): Response
    {
        $clients = $clientRepository->all();
        return $this->render('client/optionServices.html.twig', [
            'clients' => $clients,
        ]);
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
    #[Route('/{id}/delete', name: 'app_client_delete', methods: ['GET','POST'])]
    public function delete(
            Request $request,
            Client $client,
            EntityManagerInterface $entityManager
    ): Response
    {
        $form = $this->createForm(ClientDeleteType::class,$client ,[
            'action' => $this->generateUrl('app_client_delete',['id'=>$client->getId()]), 
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $client->setDeleted(YesOrNoEnum::YES);
            $entityManager->flush();
            $this->addFlash('success', 'Client deleted!');
            /*
             * ADD CHECK HEADER FOR MODAL
             */
            if($request->headers->has('turbo-frame')){
                $stream = $this->renderBlockView('client/delete.html.twig','stream_success',[
                    'client' => $client,
                ]);
                $this->addFlash('stream',$stream);
            }
            return $this->redirectToRoute('app_client_index',[], Response::HTTP_SEE_OTHER);
        }
        return $this->render('client/delete.html.twig', [
            'client' => $client
        ]);
    }
}