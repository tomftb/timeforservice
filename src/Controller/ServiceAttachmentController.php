<?php

namespace App\Controller;

use App\Entity\Service;
use App\Entity\ServiceAttachment;
use App\Form\ServiceAttachmentType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Filesystem\Filesystem;
use App\Repository\ServiceAttachmentRepository;
use App\Service\Service\Attachment;

#[Route('/service')]
class ServiceAttachmentController extends AbstractController
{
    #[Route('/{id}/attachment', name: 'app_service_attachment', methods: ['GET', 'POST'])]
    public function attachment(
            Request $request,
            Service $service,
            Attachment $attachment,
            EntityManagerInterface $entityManager,
            ServiceAttachmentRepository $serviceAttachmentRepository
    ): Response
    {
        $error = new \stdClass();
        $error->status = false;
        $error->message='Select file/files!';

        $process = false;
        $serviceId = $request->get('id');
        $serviceAttachment = new ServiceAttachment();
        $listOfAttachments = self::listAttachments($service,$serviceAttachmentRepository);
        $form = parent::createForm(ServiceAttachmentType::class, $serviceAttachment,[
            'action' => $this->generateUrl('app_service_attachment',['id'=>$serviceId]), 
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $process = true;
        }
        if(!$process){
            return $this->render('service/attachment.html.twig', [
                    'serviceAttachment' => $serviceAttachment,
                    'listOfAttachments' => $listOfAttachments,
                    'serviceId' => $serviceId,
                    'form' => $form,
                    'error'=>$error
            ]); 
        }
        /*
         * UPLOAD 
         */
        if($attachment->upload(
            $this->getParameter('app.attachment_dir').strval($request->get('id')),
            $form->get('files')->getData(),
            $service,
            $entityManager
        )){
            /*
             * ADD CHECK HEADER FOR MODAL
             */            
            if($request->headers->has('turbo-frame')){
  
            }
            else{
                /*
                * FLASH
                */
                $this->addFlash('success', 'Service files uploaded!');
               
                $stream = $this->renderBlockView('service/attachment.html.twig','stream_success',[
                    'serviceAttachment' => $serviceAttachment,
                    'serviceId' => $serviceId,
                ]);
                $this->addFlash('stream',$stream);
            }
            return $this->redirectToRoute('app_service_attachment', [ 'id' => $serviceId], Response::HTTP_SEE_OTHER);
        }
        /*
         * ALERT FLASH - TO DO
         */
        if($request->headers->has('turbo-frame')){
            
        }
        else{
            $this->addFlash('alert', 'Select file/files!');
        }
        $error->status = true;
        return $this->render('service/attachment.html.twig', [
                    'serviceAttachment' => $serviceAttachment,
                    'listOfAttachments' => $listOfAttachments,
                    'serviceId' => $serviceId,
                    'form' => $form,
                    'error'=>$error
        ],new Response(null,422)); 
    }
    public function listAttachments(
            Service $service,
            ServiceAttachmentRepository $serviceAttachmentRepository,
    ):array
    {
        $attachments = $serviceAttachmentRepository->findByService($service->getId());
        return $attachments;
    }
    #[Route('/{id}/attachmentread', name: 'app_service_attachment_read', methods: ['GET'])]
    public function read(ServiceAttachment $serviceAttachment)
    {
        $src = $this->getParameter('app.attachment_dir').strval($serviceAttachment->getService()->getId())."/".$serviceAttachment->getName();
        $imginfo = getimagesize($src);
        header("Content-type: {$imginfo['mime']}");
        readfile($src);
    }
    #[Route('/{id}/attachmentdelete', name: 'app_service_attachment_delete', methods: ['GET','POST'])]
    public function delete(Request $request, ServiceAttachment $serviceAttachment, EntityManagerInterface $entityManager):Response
    {
        //dd($request->request->get('serviceId'));
         if ($this->isCsrfTokenValid('delete'.$serviceAttachment->getId(), $request->request->get('_token'))) {
            $id = $serviceAttachment->getId();
            $entityManager->remove($serviceAttachment);
            $filesystem = new Filesystem();
            $path = $this->getParameter('app.attachment_dir').strval($serviceAttachment->getService()->getId())."/".$serviceAttachment->getName();
            $filesystem->remove($path);
            $entityManager->flush();
            $this->addFlash('success', 'Attachment deleted');
            /*
             * ADD CHECK HEADER FOR MODAL
             */
            if($request->headers->has('turbo-frame')){
                $stream = $this->renderBlockView('service/attachment_delete.html.twig','stream_success',[
                    'id' => $id
                ]);
                $this->addFlash('stream',$stream);
            }
        }
        return $this->redirectToRoute('app_service_attachment', [ 'id' => $request->request->get('serviceId')], Response::HTTP_SEE_OTHER);
    }
}
