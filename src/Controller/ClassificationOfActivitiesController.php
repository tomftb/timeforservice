<?php

namespace App\Controller;

use App\Entity\ClassificationOfActivities;
use App\Form\ClassificationOfActivitiesType;
use App\Repository\ClassificationOfActivitiesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;

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
            'classificationsOfActivities' => $classificationOfActivitiesRepository->findAllDesc(),
        ]);
    }
    #[Route('/new', name: 'app_classificationofactivities_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $classificationOfActivities = new ClassificationOfActivities();
        $form = self::createClassificationOfActivitiesForm($classificationOfActivities);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($classificationOfActivities);
            $entityManager->flush();
            $this->addFlash('success', 'Classification created');
            /*
             * CHECK REQUEST HEADER FOR OPEN PROPER WINDOW - MODAL OR NEW FULL PAGE
             */
            if($request->headers->has('turbo-frame')){
                $stream = $this->renderBlockView('classificationofactivities/new.html.twig','stream_success',[
                    'classificationOfActivities' => $classificationOfActivities
                ]);
                $this->addFlash('stream',$stream);
            }
            return $this->redirectToRoute('app_classificationofactivities_index', [], Response::HTTP_SEE_OTHER);
        }   
        return $this->render('classificationofactivities/new.html.twig', [
            'classificationOfActivities' => $classificationOfActivities,
            'form' => $form,
        ]);
    }
    #[Route('/{id}', name: 'app_classificationofactivities_show', methods: ['GET'])]
    public function show(ClassificationOfActivities $classificationOfActivities): Response
    {
         return $this->render('classificationofactivities/show.html.twig', [
            'classificationOfActivities' => $classificationOfActivities,
        ]);
    }
    #[Route('/{id}/edit', name: 'app_classificationofactivities_edit', methods: ['GET','POST'])]
    public function edit(Request $request, ClassificationOfActivities $classificationOfActivities, EntityManagerInterface $entityManager): Response
    {
        $form = self::createClassificationOfActivitiesForm($classificationOfActivities);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Classification updated');
            /*
             * CHECK REQUEST HEADER
             */
            if($request->headers->has('turbo-frame')){
                $stream = $this->renderBlockView('classificationofactivities/edit.html.twig','stream_success',[
                    'classificationOfActivities' => $classificationOfActivities
                ]);
                $this->addFlash('stream',$stream);
            }
            return $this->redirectToRoute('app_classificationofactivities_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('classificationofactivities/edit.html.twig', [
            'classificationOfActivities' => $classificationOfActivities,
            'form' => $form,
        ]);
    }
    #[Route('/{id}', name: 'app_classificationofactivities_delete', methods: ['POST'])]
    public function delete(Request $request, ClassificationOfActivities $classificationOfActivities, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$classificationOfActivities->getId(), $request->request->get('_token'))) {
            $entityManager->remove($classificationOfActivities);
            $entityManager->flush();
            $this->addFlash('success', 'Classification deleted');
        }
        return $this->redirectToRoute('app_classificationofactivities_index', [], Response::HTTP_SEE_OTHER);
    }
    /*
     * Custom Classification Form
     */
    private function createClassificationOfActivitiesForm(ClassificationOfActivities $classificationOfActivities=null ): FormInterface
    {
        $classificationOfActivities=$classificationOfActivities ?? new ClassificationOfActivities();
        return parent::createForm(ClassificationOfActivitiesType::class,$classificationOfActivities ,[
            'action' => $classificationOfActivities->getId() ? parent::generateUrl('app_classificationofactivities_edit',['id'=>$classificationOfActivities->getId()]) : parent::generateUrl( 'app_classificationofactivities_new' ), 
        ]);
    }
}