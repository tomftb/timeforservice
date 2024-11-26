<?php

namespace App\Controller;

use App\Entity\Service;

use App\Form\ServiceType;
use App\Form\ServiceDeleteType;
use App\Form\ServiceNotifyType;

use App\Repository\ServiceRepository;
use App\Repository\ClientPointRepository;
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
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use App\Model\YesOrNoEnum;
use Psr\Log\LoggerInterface;
use Symfony\Component\Mime\Part\DataPart;
use Symfony\Component\Mime\Part\File;
use App\Repository\ServiceAttachmentRepository;

#[Route('/service')]
class ServiceController extends AbstractController
{
    #[Route('/', name: 'app_service_index', methods: ['GET'])]
    public function index(
            ServiceRepository $serviceRepository,
            ClientPointRepository $clientPointRepository,
            /* VARIABLE NAME MUST EQUAL URL PART ex. ?page => $page */
            #[MapQueryParameter] int $page = 1,
            #[MapQueryParameter] string $sort = 'id',
            #[MapQueryParameter] string $sortDirection = 'DESC',
            #[MapQueryParameter] string $query = null,
            #[MapQueryParameter('clientsPoints', \FILTER_VALIDATE_INT)] array $searchClientsPoints = [],
    ): Response
    {
        $maxPerPage = 10;
        $pager = Pagerfanta::createForCurrentPageWithMaxPerPage(
                new QueryAdapter($serviceRepository->findBySearchWithClientPointQueryBuilder($query, $searchClientsPoints,$sort,$sortDirection),false),
                $page,
                $maxPerPage
        );
        return $this->render('service/index.html.twig', [
            'services' => $pager,
            'clientsPoints'=>$clientPointRepository->findAll(),
            'searchClientsPoints' => $searchClientsPoints,
            'sort' => $sort,
            'sortDirection' => $sortDirection
        ]);
    }
    #[Route('/new', name: 'app_service_new', methods: ['GET', 'POST'])]
    public function new(
            Request $request,
            ServiceRepository $serviceRepository,
            EntityManagerInterface $entityManager,
            MailerInterface $mailerInterface,
            #[MapQueryParameter] string $query = null,
            #[MapQueryParameter('clientsPoints', \FILTER_VALIDATE_INT)] array $searchClientsPoints = [],
    ): Response
    {
        $service = new Service();
        $form = self::createServiceForm($service);
        $form->handleRequest($request);
        $flashMessage="Saved";
        if ($form->isSubmitted() && $form->isValid()) {
            self::prepare($service);
            $entityManager->persist($service);
            $entityManager->flush();
            /*
             * SEND NOTIFY
             */
            if($service->getNotified()->value==='YES'){
                self::sendNotify($service,$mailerInterface);
                $service->setNotifyCounter(1);
                $flashMessage.=' & Notified';
            }
            $servicesCount = count($serviceRepository->findBySearchWithClientPoint($query, $searchClientsPoints));
            $this->addFlash('success', $flashMessage);
            /*
             * ADD CHECK HEADER FOR MODAL
             */
            if($request->headers->has('turbo-frame')){
                $stream = $this->renderBlockView('service/new.html.twig','stream_success',[
                    'service' => $service,
                    'servicesCount' => $servicesCount,
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
    public function edit(
            Request $request,
            Service $service,
            EntityManagerInterface $entityManager
    ): Response
    {
        $form = self::createServiceForm($service);
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
    #[Route('/{id}/delete', name: 'app_service_delete', methods: ['GET','POST'])]
    public function delete(
            Request $request,
            Service $service,
            EntityManagerInterface $entityManager,
            ServiceRepository $serviceRepository,
            #[MapQueryParameter] string $query = null,
            #[MapQueryParameter('clientsPoints', \FILTER_VALIDATE_INT)] array $searchClientsPoints = [],
    ): Response
    {
        $form = $this->createForm(ServiceDeleteType::class,$service ,[
            'action' => $this->generateUrl('app_service_delete',['id'=>$service->getId()]), 
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $service->setDeleted(YesOrNoEnum::YES);
            $entityManager->flush();
            $this->addFlash('success', 'Service deleted!');
            $servicesCount = count($serviceRepository->findBySearchWithClientPoint($query, $searchClientsPoints));
            /*
             * ADD CHECK HEADER FOR MODAL
             */
            if($request->headers->has('turbo-frame')){
                $stream = $this->renderBlockView('service/delete.html.twig','stream_success',[
                    'service' => $service,
                    'servicesCount' => $servicesCount,
                ]);
                $this->addFlash('stream',$stream);
            }
            return $this->redirectToRoute('app_service_index',[], Response::HTTP_SEE_OTHER);
        }
        return $this->render('service/delete.html.twig', [
            'service' => $service,
            //'form' => $form,
        ]);
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
    private function sendNotify(Service $service, MailerInterface $mailerInterface, array $attachments=[]):void
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
        $uploadAttachmentDir = $this->getParameter('app.attachment_dir').strval($service->getId());
        //dd($uploadAttachmentDir);
        foreach($attachments as $attachment){
            //dd($attachment);
           $email->addPart(new DataPart(new File($uploadAttachmentDir.'/'.$attachment->getName()), $attachment->getOriginalName(), $attachment->getType()));
        }
        $mailerInterface->send($email);
    }
    #[Route('/{id}/notify', name: 'app_service_notify', methods: ['GET','POST'])]
    public function notify(
            Request $request,
            Service $service,
            EntityManagerInterface $entityManager,
            MailerInterface $mailerInterface,
            ServiceAttachmentRepository $serviceAttachmentRepository,
    ): Response
    {
        $form = $this->createForm(ServiceNotifyType::class,$service ,[
            'action' => $this->generateUrl('app_service_notify',['id'=>$service->getId()]), 
        ]);
        $attachments = $serviceAttachmentRepository->findByService($service->getId());
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            self::sendNotify($service,$mailerInterface,$attachments);
            $notifyCounter = intval($service->getNotifyCounter(),10);
            $service->setNotifyCounter($notifyCounter+1);
            $service->setNotified(YesOrNoEnum::YES);
            $entityManager->flush();
            $this->addFlash('success', 'Notified!');
            /*
             * ADD CHECK HEADER FOR MODAL
             */
            if($request->headers->has('turbo-frame')){
                $stream = $this->renderBlockView('service/notify.html.twig','stream_success',[
                    'service' => $service,
                ]);
                $this->addFlash('stream',$stream);
            }
            return $this->redirectToRoute('app_service_index',[], Response::HTTP_SEE_OTHER);
        }
        return $this->render('service/notify.html.twig', [
            'service' => $service,
            'form' => $form,
        ]);
    }
}