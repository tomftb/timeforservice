<?php

namespace App\Controller;

use App\Entity\Service;
use App\Entity\ServiceAttachment;
use App\Form\ServiceAttachmentType;

use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

use Doctrine\ORM\EntityManagerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\FormInterface;

#[Route('/service')]
class ServiceAttachmentController extends AbstractController
{
    #[Route('/{id}/attachment', name: 'app_service_attachment', methods: ['GET', 'POST'])]
    public function attachment(
            Request $request,
            Service $service,
            EntityManagerInterface $entityManager,
            SluggerInterface $slugger,
            #[Autowire('%kernel.project_dir%/uploads/attachments')] string $uploadAttachmentDir
    ): Response
    {
        //dd($service->getId());
        $serviceId = $request->get('id');
        $serviceAttachment = new ServiceAttachment();
        $form = parent::createForm(ServiceAttachmentType::class, $serviceAttachment);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $brochureFile */
            $attachmentsFiles=$form->get('files')->getData();


            if(empty($attachmentsFiles)){
                /*
                 * RENDER BLOCK
                 */
            }
            else{
                foreach($attachmentsFiles as $attachment){
                    $serviceAttachment = new ServiceAttachment();
                    $originalFilename = pathinfo($attachment->getClientOriginalName(), PATHINFO_FILENAME);
                    $safeFilename = $slugger->slug($originalFilename);
                    $fileExtension = $attachment->guessExtension();
                    $fileMimeType = $attachment->getMimeType();
                    $fileSize = $attachment->getSize();
                    $newFilename = $safeFilename.'-'.uniqid().'.'.$fileExtension;
                    try {
                        $attachment->move($uploadAttachmentDir, $newFilename);
                    } catch (FileException $e) {
                        // ... handle exception if something happens during file upload
                    }
                    $serviceAttachment->setName($newFilename);
                    $serviceAttachment->setOriginalName($originalFilename);
                    $serviceAttachment->setSize($fileSize);
                    $serviceAttachment->setType($fileMimeType);
                    $serviceAttachment->setExtension($fileExtension);
                    $serviceAttachment->setService($service);
                    $entityManager->persist($serviceAttachment);
                    $entityManager->flush();
                }
            } 
            $entityManager->flush();
                $this->addFlash('success', 'Service files uploaded!');
            /*
             * ADD CHECK HEADER FOR MODAL
             */
            if($request->headers->has('turbo-frame')){
                $stream = $this->renderBlockView('service/attachment.html.twig','stream_success',[
                    'serviceAttachment' => $serviceAttachment,
                    'serviceId' => $serviceId,
                ]);
                $this->addFlash('stream',$stream);
            }
            return $this->redirectToRoute('app_service_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('service/attachment.html.twig', [
            'serviceAttachment' => $serviceAttachment,
            'serviceId' => $serviceId,
            'form' => $form,
        ]);
    }
}
