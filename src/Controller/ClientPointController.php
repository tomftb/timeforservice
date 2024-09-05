<?php

namespace App\Controller;

use App\Entity\ClientPoint;
use App\Form\ClientPointType;
use App\Repository\ClientPointRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Description of ClientPointController
 *
 * @author Tomasz Borczynski
 */
#[Route('/clientpoint')]
class ClientPointController extends AbstractController{
    
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
        $form = $this->createForm(ClientPointType::class, $clientPoint);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($clientPoint);
            $entityManager->flush();
            $this->addFlash('success', 'Client Point created');  
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
        $form = $this->createForm(ClientPointType::class, $clientPoint);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Client Point updated');
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
            $entityManager->remove($clientPoint);
            $entityManager->flush();
            $this->addFlash('success', 'Client Point deleted');
        }
        return $this->redirectToRoute('app_clientpoint_index', [], Response::HTTP_SEE_OTHER);
    }
}