<?php

namespace App\Controller;

use App\Entity\Employe;
use App\Form\EmployeType;
use App\Repository\EmployeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;

/**
 * Description of EmployeController
 *
 * @author Tomasz Borczynski
 */
#[Route('/employe')]
class EmployeController extends AbstractController{
    
    #[Route('/', name: 'app_employe_index', methods: ['GET'])]
    public function index(EmployeRepository $employeRepository): Response
    {
        return $this->render('employe/index.html.twig', [
            'employees' => $employeRepository->findAllDesc(),
        ]);
    }
    #[Route('/new', name: 'app_employe_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $employe = new Employe();
        $form = self::createEmployeForm($employe);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($employe);
            $entityManager->flush();
            $this->addFlash('success', 'Employe added');
            /*
             * CHECK REQUEST HEADER FOR OPEN PROPER WINDOW - MODAL OR NEW FULL PAGE
             */
            if($request->headers->has('turbo-frame')){
                $stream = $this->renderBlockView('employe/new.html.twig','stream_success',[
                    'employe' => $employe
                ]);
                $this->addFlash('stream',$stream);
            }
            return $this->redirectToRoute('app_employe_index', [], Response::HTTP_SEE_OTHER);
        }   
        return $this->render('employe/new.html.twig', [
            'employe' => $employe,
            'form' => $form,
        ]);
    }
    #[Route('/{id}', name: 'app_employe_show', methods: ['GET'])]
    public function show(Employe $employe): Response
    {
         return $this->render('employe/show.html.twig', [
            'employe' => $employe,
        ]);
    }
    #[Route('/{id}/edit', name: 'app_employe_edit', methods: ['GET','POST'])]
    public function edit(Request $request, Employe $employe, EntityManagerInterface $entityManager): Response
    {
        $form = self::createEmployeForm($employe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Employe updated');
            /*
             * CHECK REQUEST HEADER
             */
            if($request->headers->has('turbo-frame')){
                $stream = $this->renderBlockView('employe/edit.html.twig','stream_success',[
                    'employe' => $employe
                ]);
                $this->addFlash('stream',$stream);
            }
            return $this->redirectToRoute('app_employe_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('employe/edit.html.twig', [
            'employe' => $employe,
            'form' => $form,
        ]);
    }
    #[Route('/{id}', name: 'app_employe_delete', methods: ['POST'])]
    public function delete(Request $request, Employe $employe, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$employe->getId(), $request->request->get('_token'))) {
            $entityManager->remove($employe);
            $entityManager->flush();
            $this->addFlash('success', 'Employe deleted');
        }
        return $this->redirectToRoute('app_employe_index', [], Response::HTTP_SEE_OTHER);
    }
    /*
     * Custom Classification Form
     */
    private function createEmployeForm(Employe $employe=null ): FormInterface
    {
        $employe=$employe ?? new ClassificationOfActivities();
        return parent::createForm(EmployeType::class,$employe ,[
            'action' => $employe->getId() ? parent::generateUrl('app_employe_edit',['id'=>$employe->getId()]) : parent::generateUrl( 'app_employe_new' ), 
        ]);
    }
}