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
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use App\Model\YesOrNoEnum;
use Psr\Log\LoggerInterface;
use App\Repository\ServiceAttachmentRepository;
use App\Service\Service\Notify;
use App\Service\Service\Save;
use App\Service\Service\Filter;

#[Route('/service')]
class ServiceController extends AbstractController
{
    #[Route('/', name: 'app_service_index', methods: ['GET'])]
    public function index(
            ServiceRepository $serviceRepository,
            ClientPointRepository $clientPointRepository,
            Filter $filter,
            /* VARIABLE NAME MUST EQUAL URL PART ex. ?page => $page */
            #[MapQueryParameter] int $page = 1,
            #[MapQueryParameter] string $sort = 'id',
            #[MapQueryParameter] string $sortDirection = 'DESC',
            #[MapQueryParameter] string $query = null,
            #[MapQueryParameter('clientsPoints', \FILTER_VALIDATE_INT)] array $searchClientsPoints = [],
            #[MapQueryParameter('filters', null)] array $selectedFilters = [],
    ): Response
    {
        $maxPerPage = 10;
        $pager = Pagerfanta::createForCurrentPageWithMaxPerPage(
                new QueryAdapter($serviceRepository->findBySearchWithClientPointQueryBuilder($query, $searchClientsPoints,$sort,$sortDirection,$selectedFilters),false),
                $page,
                $maxPerPage
        );
        return $this->render('service/index.html.twig', [
            'services' => $pager,
            'clientsPoints'=>$clientPointRepository->findAll(),
            'searchClientsPoints' => $searchClientsPoints,
            'sort' => $sort,
            'sortDirection' => $sortDirection,
            'filters'=> $filter->getAll(),
            'selectedFilters' => $selectedFilters,
        ]);
    }
    #[Route('/new', name: 'app_service_new', methods: ['GET', 'POST'])]
    public function new(
            Request $request,
            ServiceRepository $serviceRepository,
            ClientPointRepository $clientPointRepository,
            EntityManagerInterface $entityManager,
            MailerInterface $mailerInterface,
            Notify $notify,
            Save $save,
            #[MapQueryParameter] string $query = null,
            #[MapQueryParameter('clientsPoints', \FILTER_VALIDATE_INT)] array $searchClientsPoints = [],
    ): Response
    {
        $service = new Service();
        $form = self::createServiceForm($service);
        $form->handleRequest($request);
        $flashMessage="Saved";
        if ($form->isSubmitted() && $form->isValid()) {
            $save->save($service,$entityManager,$form);
            /*
             * SEND NOTIFY
             */
            if($service->getNotified()->value==='YES'){
                $notify->send($service,$mailerInterface);
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
            Save $save,
            EntityManagerInterface $entityManager
    ): Response
    {
        $form = self::createServiceForm($service);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $save->save($service,$entityManager,$form);
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

    #[Route('/{id}/notify', name: 'app_service_notify', methods: ['GET','POST'])]
    public function notify(
            Request $request,
            Service $service,
            Notify $notify,
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
            $notify->send($service,$mailerInterface,$attachments);
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