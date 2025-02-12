<?php

namespace App\Controller;

use App\Repository\ClassificationOfActivitiesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


/**
 * Description of ClassificationOfActivitiesController
 *
 * @author Tomasz Borczynski
 */
#[Route('/classificationofactivities')]
class ClassificationOfActivitiesController extends AbstractController{
    
    #[Route('/', name: 'app_classificationofactivities_index', methods: ['GET'])]
    public function index(ClassificationOfActivitiesRepository $classificationOfActivitiesRepository): Response
    {
        return $this->render('classificationofactivities/index.html.twig', [
            'classificationsOfActivities' => $classificationOfActivitiesRepository->findAll(),
        ]);
    }
}