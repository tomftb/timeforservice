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
    }
    #[Route('/new', name: 'app_service_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $service = new Service();
        $form = $this->createServiceForm($service);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /*
             * SET ClassificationOfActivities
             */
            $service->setUnit($service->getClassificationOfActivities()->getUnit());
            $service->setCode($service->getClassificationOfActivities()->getCode());
            $service->setName($service->getClassificationOfActivities()->getName());
            $service->setRate(self::getClientClassificationOfActivitiesPrice($service));
            /*
             * SET Client
             */
            $service->setRoutePrice($service->getClientPoint()->getClient()->getKilometerRate());
            $entityManager->persist($service);
            $entityManager->flush();
            $this->addFlash('success', 'Saved');
            /*
             * ADD CHECK HEADER FOR MODAL
             */
            if($request->headers->has('turbo-frame')){
                $stream = $this->renderBlockView('service/new.html.twig','stream_success',[
                    'service' => $service
                ]);
                $this->addFlash('stream',$stream);
            }
            return $this->redirectToRoute('app_service_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('service/new.html.twig', [
            'service' => $service,
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
            /*
             * SET ClassificationOfActivities
             */
            $service->setRate(self::getClientClassificationOfActivitiesPrice($service));
            $service->setUnit($service->getClassificationOfActivities()->getUnit());
            $service->setCode($service->getClassificationOfActivities()->getCode());
            $service->setName($service->getClassificationOfActivities()->getName());
            $service->setRate($service->getClassificationOfActivities()->getClientClassificationOfActivities()->current()->getPrice());
            /*
             * SET Client
             */
            $service->setRoutePrice($service->getClientPoint()->getClient()->getKilometerRate());
            $entityManager->flush();
            $this->addFlash('success', 'Service updated!');
            /*
             * ADD CHECK HEADER FOR MODAL
             */
            if($request->headers->has('turbo-frame')){
                $stream = $this->renderBlockView('service/edit.html.twig','stream_success',[
                    'service' => $service
                ]);
                $this->addFlash('stream',$stream);
            }
           
            return $this->redirectToRoute('app_service_edit', ['id'=>$service->getId()], Response::HTTP_SEE_OTHER);
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
            $id = $service->getId();
            $entityManager->remove($service);
            $entityManager->flush();
            $this->addFlash('success', 'Service deleted!');
            
            /*
             * ADD CHECK HEADER FOR MODAL
             */
            if($request->headers->has('turbo-frame')){
                $stream = $this->renderBlockView('service/delete.html.twig','stream_success',[
                    'id' => $id
                ]);
                $this->addFlash('stream',$stream);
            }
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
    private function getClientClassificationOfActivitiesPrice(Service $service):?float
    {
        if($service->getClassificationOfActivities() === null)
        {
            /* TO DO */
            //dd('chose service');
        }
        //dd($service);
        $classificationId = $service->getClassificationOfActivities()->getId();
        $clientClassificationOfActivities = $service->getClientPoint()->getClient()->getClientClassificationOfActivities()->getValues();
        if(empty($clientClassificationOfActivities))
        {
            /* TO DO */
            //dd('set service list');
        }
        foreach($clientClassificationOfActivities as $clientClassification ){
            if($clientClassification->getId() === $classificationId){
                return $clientClassification->getPrice();
            }
        }
        return null;
    }
}