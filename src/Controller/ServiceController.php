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
use App\Service\ConvertTime;
use App\Controller\MailerController;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Address;

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
    public function new(Request $request, EntityManagerInterface $entityManager, MailerInterface $mailerInterface): Response
    {
        $service = new Service();
        $form = $this->createServiceForm($service);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            self::prepare($service);
            $entityManager->persist($service);
            $entityManager->flush();
            $this->addFlash('success', 'Saved');
            /*
             * SEND NOTIFY
             */
             self::sendNotify($service,$mailerInterface);
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
            self::prepare($service);
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
    private function getClientClassificationOfActivitiesPrice(Service $service):float
    {
        if($service->getClassificationOfActivities() === null)
        {
            /* TO DO */
            //dd('chose service');
            return 0;
        }
        //dd($service);
        $classificationId = $service->getClassificationOfActivities()->getId();
        $clientClassificationOfActivities = $service->getClientPoint()->getClient()->getClientClassificationOfActivities()->getValues();
        if(empty($clientClassificationOfActivities))
        {
            /* TO DO */
            //dd('set service list');
            return 0;
        }
        foreach($clientClassificationOfActivities as $clientClassification ){
            if($clientClassification->getClassification()->getId() === $classificationId){
                return $clientClassification->getPrice();
            }
        }
        //dd("NOT FOUND");
        return 0;
    }
    private function prepare(Service $service):Service
    {
        /*
         * SET ROUTE
         */
        $route = $service->getRoute();
        if($route === null){
            $route = 0;
            $service->setRoute(0);
        }
        $kilometerRate = $service->getClientPoint()->getClient()->getKilometerRate();
        $service->setRoutePrice($kilometerRate);
        $service->setRouteCost($kilometerRate * $route);
        /*
         * SET ClassificationOfActivities
         */
        $service->setUnit($service->getClassificationOfActivities()->getUnit());
        $service->setCode($service->getClassificationOfActivities()->getCode());
        $service->setName($service->getClassificationOfActivities()->getName());
        /*
         * SET COST
         */
        $rate = self::getClientClassificationOfActivitiesPrice($service);
        $service->setRate($rate);
        $convertTime = new ConvertTime();
        $convertTime->add($service->getTime());
        $service->setRealTime($convertTime->get());
        $service->setCost($convertTime->get()*$rate);
        return $service;
    }
    private function sendNotify(Service $service, MailerInterface $mailerInterface):void
    {       
        (string) $emailTo = '';
        (string) $emailCc = '';
        /*
         * SET CLIENT EMAIL
         */
        if($service->getClientPoint()->getClient()->getEmail()!==null && $service->getClientPoint()->getClient()->getSendNotify()->value==='YES'){
            $emailTo = $service->getClientPoint()->getClient()->getEmail();
        }
        /*
         * SET CLIENT POINT EMAIL
         */
        if($service->getClientPoint()->getEmail()!==null && $service->getClientPoint()->getSendNotify()->value==='YES'){
            $emailCc = $service->getClientPoint()->getEmail();
            
        }
        /*
         * CHECK EMAIL
         */
        if($emailTo === '' && $emailCc === ''){
            return;
        }
        /*
         * CHECK EMAIL TO
         */
        if($emailTo === '' && $emailCc!==''){
            $emailTo = $emailCc;
            $emailCc = '';
        }
        (string) $journey = '';
        (string) $realTime = strval($service->getRealTime())."h";
        //dd($service->getRoute());
        if($service->getRoute() !== 0.0 && $service->getRoute() !== 0){
            $journey = ' + dojazd';
        }
        $subject = 'Serwis ['.$service->getEndedAt()->format("d.m.Y").'] '.$service->getClientPoint()->getName()." ".$service->getClientPoint()->getStreet()." - ".$realTime.$journey;
        $html = nl2br($service->getDescription()."\r\nCzas pracy ".$realTime.$journey."\r\n<span style=\"font-size:10px;color:rgb(152,152,152)\">--\r\nWiadomość wysłana z aplikacji timeForService@TimeForIT Tomasz Borczyński</span>");
        $email = new Email();
        $email->from(new Address('tborczynski87@gmail.com','TimeForIT Tomasz Borczyński'))
            ->to($emailTo)
            ->bcc('tborczynski87@gmail.com')
            ->replyTo('tborczynski87@gmail.com')
            ->priority(Email::PRIORITY_HIGH)
            ->subject($subject)
            ->html($html);
        if($emailCc!==''){
            $email->cc($emailCc);
        }
        $mailerInterface->send($email);
    }
}