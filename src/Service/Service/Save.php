<?php

namespace App\Service\Service;

use App\Entity\Service;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Service\ConvertTime;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\Service\Attachment;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Form\Form;
/**
 * Description of Notify
 *
 * @author Tomasz Borczynski
 */
class Save extends AbstractController
{
    private Service $service;
    private SluggerInterface $slugger;
    private Form $form;
    private EntityManagerInterface $entityManager;
    
    public function __construct(SluggerInterface $slugger){
        $this->slugger = $slugger;
    }
    public function save(Service $service,EntityManagerInterface $entityManager,Form $form):void
    {
        $this->service = $service;
        $this->entityManager = $entityManager;
        $this->form = $form;
        self::prepare();
        self::setClient();
        self::setClientPoint();
        $this->entityManager->persist($this->service);
        $this->entityManager->flush();
        /*
         * ATTACHMENT
         */
        self::attachment();
    }
    public function prepare():void
    {
        /*
         * SET ROUTE
         */
        $route = $this->service->getRoute();
        if($route === null){
            $route = 0;
            $this->service->setRoute(0);
        }
        $kilometerRate = $this->service->getClientPoint()->getClient()->getKilometerRate();
        $this->service->setRoutePrice($kilometerRate);
        $this->service->setRouteCost($kilometerRate * $route);
        /*
         * SET ClassificationOfActivities
         */
        $this->service->setUnit($this->service->getClassificationOfActivities()->getUnit());
        $this->service->setCode($this->service->getClassificationOfActivities()->getCode());
        $this->service->setName($this->service->getClassificationOfActivities()->getName());
        /*
         * SET COST
         */
        $rate = self::getClientClassificationOfActivitiesPrice();
        $this->service->setRate($rate);
        $convertTime = new ConvertTime();
        $convertTime->add($this->service->getTime());
        $this->service->setRealTime($convertTime->get());
        $this->service->setCost($convertTime->get()*$rate);
        /*
         * SET USER
         * $this->getUser()->getId()
         */
        $this->service->setUser($this->getUser());
    }
    private function getClientClassificationOfActivitiesPrice():float
    {
        if($this->service->getClassificationOfActivities() === null)
        {
            /* TO DO */
            //dd('chose service');
            return 0;
        }
        //dd($service);
        $classificationId = $this->service->getClassificationOfActivities()->getId();
        $clientClassificationOfActivities = $this->service->getClientPoint()->getClient()->getClientClassificationOfActivities()->getValues();
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
    private function setClient(){
        $this->service->setClientName($this->service->getClientPoint()->getClient()->getName());
        $this->service->setClientStreet($this->service->getClientPoint()->getClient()->getStreet());
        $this->service->setClientZipCode($this->service->getClientPoint()->getClient()->getZipCode());
        $this->service->setClientTown($this->service->getClientPoint()->getClient()->getTown());
        $this->service->setClientEmail($this->service->getClientPoint()->getClient()->getEmail());
        $this->service->setClientNin($this->service->getClientPoint()->getClient()->getNin());
        $this->service->setClientSendNotify($this->service->getClientPoint()->getClient()->getSendNotify());
    }
    private function setClientPoint(){
        $this->service->setClientPointName($this->service->getClientPoint()->getName());
        $this->service->setClientPointStreet($this->service->getClientPoint()->getStreet());
        $this->service->setClientPointZipCode($this->service->getClientPoint()->getZipCode());
        $this->service->setClientPointTown($this->service->getClientPoint()->getTown());
        $this->service->setClientPointEmail($this->service->getClientPoint()->getEmail());
        $this->service->setClientPointPhoneNumber($this->service->getClientPoint()->getPhoneNumber());
        $this->service->setClientPointSendNotify($this->service->getClientPoint()->getSendNotify());
    }
    public function get():Service{
        return $this->service;
    }
    private function attachment(){
        $attachment = new Attachment($this->slugger);
        $attachment->upload(
            $this->getParameter('app.attachment_dir').strval($this->service->getId()),
            $this->form->get('files')->getData(),
            $this->service,
            $this->entityManager
        );
    }
}
