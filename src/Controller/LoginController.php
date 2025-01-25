<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Annotation\Route;

use Pagerfanta\Pagerfanta;
use Pagerfanta\Doctrine\ORM\QueryAdapter;

class LoginController extends AbstractController
{
    #[Route('/login', name: 'app_login')]
    public function login(
    ): Response
    {
        return $this->render('login/index.html.twig', [
        ]);
    }
}