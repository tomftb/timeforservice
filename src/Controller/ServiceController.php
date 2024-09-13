<?php

namespace App\Controller;

use App\Entity\Service;
use App\Form\ServiceType;
use App\Repository\ServiceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\FormInterface;

#[Route('/service')]
class ServiceController extends AbstractController
{
    #[Route('/', name: 'app_service_index', methods: ['GET'])]
    public function index(ServiceRepository $serviceRepository): Response
    {
        return $this->render('service/index.html.twig', [
            'services' => $serviceRepository->findByWithClientPoint()
        ]);
        //return $this->render('service/index.html.twig', [
        //    'services' => $serviceRepository->findBy([], ['id' => 'DESC'])
        //]);
    }
    #[Route('/new', name: 'app_service_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        sleep(2);
        $service = new Service();
        $form = $this->createServiceForm($service);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($service);
            $entityManager->flush();
            $this->addFlash('success', 'Saved');
            return $this->redirectToRoute('app_service_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('service/new.html.twig', [
            'voyage' => $service,
            'form' => $form,
        ]);
    }
    #[Route('/{id}', name: 'app_service_show', methods: ['GET'])]
    public function show(Service $service): Response
    {
        return $this->render('service/show.html.twig', [
            'service' => $service,
        ]);
    }
    #[Route('/{id}/edit', name: 'app_service_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Service $service, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createServiceForm($service);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Service updated!');
            return $this->redirectToRoute('app_service_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('service/edit.html.twig', [
            'service' => $service,
            'form' => $form,
        ]);
    }
    #[Route('/{id}', name: 'app_service_delete', methods: ['POST'])]
    public function delete(Request $request, Service $service, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$service->getId(), $request->request->get('_token'))) {
            $entityManager->remove($service);
            $entityManager->flush();
            $this->addFlash('success', 'Service deleted!');
        }
        return $this->redirectToRoute('app_service_index', [], Response::HTTP_SEE_OTHER);
    }
    private function createServiceForm(Service $service=null ): FormInterface
    {
        $service=$service ?? new Service();
        return $this->createForm(ServiceType::class,$service ,[
            'action' => $service->getId() ? $this->generateUrl('app_service_edit',['id'=>$service->getId()]) : $this->generateUrl( 'app_service_new' ), 
        ]);
    }
}