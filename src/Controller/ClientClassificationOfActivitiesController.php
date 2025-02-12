<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\ClientClassificationOfActivities;
use App\Repository\ClassificationOfActivitiesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\Error;

/**
 * Description of ClientClassificationOfActivitiesController
 *
 * @author Tomasz Borczynski
 */
#[Route('/client')]
class ClientClassificationOfActivitiesController extends AbstractController{

    private Client $client;
    private EntityManagerInterface $entityManager;
    private ClassificationOfActivitiesRepository $classificationOfActivitiesRepository;
    private array $clientClassificationOfActivities;
    private object $error;
    
    public function __construct(){

    }

    #[Route('/{id}/cooperation', name: 'app_client_cooperation', methods: ['GET', 'POST'])]
    public function cooperation(Request $request, Client $client,ClassificationOfActivitiesRepository $classificationOfActivitiesRepository, EntityManagerInterface $entityManager): Response
    {
        $this->client = $client;
        $this->entityManager = $entityManager;
        $this->request = $request;
        $this->error = new Error();        
        $this->classificationOfActivitiesRepository = $classificationOfActivitiesRepository;
        $this->clientClassificationOfActivities = $this->entityManager->getRepository(ClientClassificationOfActivities::class)->findByClientId($this->client->getId());


        $classificationOfActivities = self::getSetup();

        if($request->getMethod() === "GET"){
            return $this->render('client/cooperation.html.twig', [
                'client' => $client,
                'classifications' => $classificationOfActivities,
                'error' => $this->error->get(),
            ]);
        }
        if($request->getMethod() !== "POST"){
            $this->error->set("WRONG METHOD");
            return $this->render('client/cooperation.html.twig', [
                'client' => $client,
                'classifications' => $classificationOfActivities,
                'error' => $this->error->get(),
            ],new Response(null,422));
        }

        $submittedToken = $request->getPayload()->get('_csrf_token');

        if (!$this->isCsrfTokenValid('cooperation', $submittedToken)) {
            $this->error->set("WRONG CSRF");
            return $this->render('client/cooperation.html.twig', [
                    'client' => $client,
                    'classifications' => $classificationOfActivities,
                    'error' => $this->error->get(),
            ],new Response(null,422));
        }
        /*
         * REMVOE OLD CLIENT CLASSIFICATION OF ACTIVITIES
         */
        self::remove();
        /*
         * INSERT NEW CLIENT CLASSIFICATION OF ACTIVITIES
         */
        self::insert();
        /*
         * ADD CHECK HEADER FOR MODAL
         */
        if($request->headers->has('turbo-frame')){
            $stream = $this->renderBlockView('service/attachment.html.twig','stream_success',[
                'client' => $client,
                'classifications' => $classificationOfActivities,
                'error' => $this->error->get(),
            ]);
            $this->addFlash('stream',$stream);
        }
        return $this->redirectToRoute('app_client_index', [], Response::HTTP_SEE_OTHER);
    }

    private function getSetup():array{
        $found = false;
        $price = 0;

        $classificationOfActivities = $this->classificationOfActivitiesRepository->findAll(); //shortFindAll();

        $clientClassificationOfActivities = $this->clientClassificationOfActivities;
        foreach($classificationOfActivities as &$classification){

            foreach($clientClassificationOfActivities as $clientClassification){
                if($clientClassification->getClassification()->getId() === $classification->getId()){
                    $found = true;
                    $price = $clientClassification->getPrice();
                    break;
                }
            }
            if($found){
                $classification->setPrice($price);
            }
            $found = false;
            $price = 0;
        }
        return $classificationOfActivities;
    }

    private function remove(){
        foreach($this->clientClassificationOfActivities as $clientClassification){
            $this->entityManager->remove($clientClassification);
        }
        $this->entityManager->flush();
    }

    private function insert(){

        $date = $this->request->request->all();

        UNSET($date['_csrf_token']);

        foreach($date as $key => $value){
            $clientClassificationOfActivities = new ClientClassificationOfActivities();
            $clientClassificationOfActivities->setPrice($value);
            $clientClassificationOfActivities->setClient($this->client);

            foreach($this->classificationOfActivitiesRepository->findAll() as $classification){
                if($classification->getId() === intval($key,10)){
                    $clientClassificationOfActivities->setClassification($classification);
                    break;
                }
            }
            $this->entityManager->persist($clientClassificationOfActivities);
        }
       $this->entityManager->flush();
    }
}