<?php

namespace App\Service\Service;

use App\Entity\Service;
use App\Entity\ServiceAttachment;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Description of Attachment
 *
 * @author Tomasz Borczynski
 */
class Attachment {
    
    private $slugger;
    
    public function __construct(SluggerInterface $slugger) {
        $this->slugger = $slugger;
    }

    public function upload(
                            $dir,
                            $files,
                            Service $service,
                            EntityManagerInterface $entityManager,
    ):bool{
        /** @var UploadedFile $brochureFile */
        $uploadAttachmentDir = $dir;
        $attachmentsFiles=$files;
        if(empty($attachmentsFiles)){
            /*
             * RENDER BLOCK
             */
            return false;
        }
        
        $filesystem = new Filesystem();
        if(!$filesystem->exists($uploadAttachmentDir)){
            $filesystem->mkdir($uploadAttachmentDir, 0755);
        }
        foreach($attachmentsFiles as $attachment){
                    $serviceAttachment = new ServiceAttachment();
                    $originalFilename = pathinfo($attachment->getClientOriginalName(), PATHINFO_FILENAME);
                    $safeFilename = $this->slugger->slug($originalFilename);
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
        $entityManager->flush();
        return true;
    }
}
