<?php

namespace App\Controller;

use App\Entity\ClientPoint;
use App\Entity\Service;
use App\Form\ClientPointType;
use App\Repository\ClientPointRepository;
use App\Repository\ServiceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\BinaryFileResponse;


/**
 * Description of ClientPointController
 *
 * @author Tomasz Borczynski
 */
#[Route('/clientpoint')]
class ClientPointController extends AbstractController{
    
    public function __construct(
        private string $appTmp
    ){
    }
    
    #[Route('/', name: 'app_clientpoint_index', methods: ['GET'])]
    public function index(ClientPointRepository $clientPointRepository): Response
    {
        return $this->render('clientpoint/index.html.twig', [
            'clientsPoints' => $clientPointRepository->findAll(),
        ]);
    }
    #[Route('/new', name: 'app_clientpoint_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $clientPoint = new ClientPoint();
        $form = $this->createClientPointForm($clientPoint);
        $form->handleRequest($request);    
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($clientPoint);
            $entityManager->flush();
            $this->addFlash('success', 'Client Point created');
            /*
             * CHECK REQUEST HEADER FOR OPEN PROPER WINDOW - MODAL OR NEW FULL PAGE
             */
            if($request->headers->has('turbo-frame')){
                $stream = $this->renderBlockView('clientpoint/new.html.twig','stream_success',[
                    'clientPoint' => $clientPoint
                ]);
                $this->addFlash('stream',$stream);
            }
            return $this->redirectToRoute('app_clientpoint_index', [], Response::HTTP_SEE_OTHER);
        }       
        return $this->render('clientpoint/new.html.twig', [
            'clientPoint' => $clientPoint,
            'form' => $form,
        ]);
    }
    #[Route('/{id}', name: 'app_clientpoint_show', methods: ['GET'])]
    public function show(ClientPoint $clientPoint): Response
    {
        return $this->render('clientpoint/show.html.twig', [
            'clientPoint' => $clientPoint,
        ]);
    }
    #[Route('/{id}/edit', name: 'app_clientpoint_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ClientPoint $clientPoint, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createClientPointForm($clientPoint);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Client Point updated');
            /*
             * CHECK REQUEST HEADER
             */
            if($request->headers->has('turbo-frame')){
                $stream = $this->renderBlockView('clientpoint/edit.html.twig','stream_success',[
                    'clientPoint' => $clientPoint
                ]);
                $this->addFlash('stream',$stream);
            }
            return $this->redirectToRoute('app_clientpoint_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('clientpoint/edit.html.twig', [
            'clientPoint' => $clientPoint,
            'form' => $form,
        ]);
    }
    #[Route('/{id}', name: 'app_clientpoint_delete', methods: ['POST'])]
    public function delete(Request $request, ClientPoint $clientPoint, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$clientPoint->getId(), $request->request->get('_token'))) {
            $id = $clientPoint->getId();
            $entityManager->remove($clientPoint);
            $entityManager->flush();
            $this->addFlash('success', 'Client Point deleted');
            /*
             * ADD CHECK HEADER FOR MODAL
             */
            if($request->headers->has('turbo-frame')){
                $stream = $this->renderBlockView('clientpoint/delete.html.twig','stream_success',[
                    'id' => $id
                ]);
                $this->addFlash('stream',$stream);
            }
        }
        return $this->redirectToRoute('app_clientpoint_index', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/{id}/card', name: 'app_clientpoint_card',methods:['GET'])]
    public function showCard(ClientPoint $clientPoint): Response
    {
        return $this->render('clientpoint/_card.html.twig',[
           'clientPoint' => $clientPoint 
        ]);
    }
    /*
     * Custom Client Point Form
     */
    private function createClientPointForm(ClientPoint $clientPoint=null ): FormInterface
    {
        $clientPoint=$clientPoint ?? new ClientPoint();
        return $this->createForm(ClientPointType::class,$clientPoint ,[
            'action' => $clientPoint->getId() ? $this->generateUrl('app_clientpoint_edit',['id'=>$clientPoint->getId()]) : $this->generateUrl( 'app_clientpoint_new' ), 
        ]);
    }
    #[Route('/{id}/services', name: 'app_clientpoint_services', methods: ['GET'])]
    public function services(ClientPoint $clientPoint,ServiceRepository $serviceRepository): Response
    {
        return $this->render('clientpoint/services.html.twig', [
            'clientPoint' => $clientPoint,
            'services' => $serviceRepository->findByClientPointId($clientPoint->getId(),'DESC')
        ]);
    }
    #[Route('/{id}/export_services', name: 'app_clientpoint_export_services', methods: ['GET'])]
    public function exportServices(ClientPoint $clientPoint,ServiceRepository $serviceRepository): Response
    {
        //dd($this->getParameter('%APP_TMP%'));
        //dd($this->appTmp);
        return $this->file($this->appTmp."farmline_logo.png");
    }
}