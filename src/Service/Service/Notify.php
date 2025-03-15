<?php

namespace App\Service\Service;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\MailerInterface;
use App\Entity\Service;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Part\DataPart;
use Symfony\Component\Mime\Part\File;

/**
 * Description of Notify
 *
 * @author Tomasz Borczynski
 */
class Notify extends AbstractController
{
    
    public function __construct(){

    }
    public function send(Service $service, MailerInterface $mailerInterface, array $attachments=[]):void
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
        (string) $journeySubject = '';
        (string) $realTime = "<br/>Czas pracy - ".strval($service->getRealTime())."h";
        (string) $realTimeSubject = " Czas pracy - ".strval($service->getRealTime())."h";
        (string) $materials = '';
        //dd($service->getRoute());
        /*
         * CHECK ROUTE
         */
        if($service->getRoute() !== 0.0 && $service->getRoute() !== 0){
            $journey = "<br/>Dojazd - ".strval($service->getRoute())."km";
            $journeySubject = " Dojazd - ".strval($service->getRoute())."km";
        }
        /*
         * CHECK MATERIALS COSTS
         */
        if($service->getMaterialCosts() !== 0.0 && $service->getMaterialCosts() !== 0 && $service->getMaterialCosts() !== null){
            $materials = "<br/>Koszt materiałów - ".$service->getMaterialCosts()."zł";
        }
        $subject = 'Serwis ['.$service->getEndedAt()->format("d.m.Y").'] '.$service->getClientPoint()->getName()." ".$service->getClientPoint()->getStreet()." -".$realTimeSubject.$journeySubject;
        $html = nl2br($service->getDescription());
        $html.=$realTime.$journey.$materials."<br/><span style=\"font-size:10px;color:rgb(152,152,152)\">--<br/>Wiadomość wysłana z aplikacji timeForService@TimeForIT Tomasz Borczyński</span>";
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
}
