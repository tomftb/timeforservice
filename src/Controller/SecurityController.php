<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $errorIsValid = new \stdClass();
        $errorIsValid->status = false;
        $errorIsValid->data="";
        
        $error = $authenticationUtils->getLastAuthenticationError();

        $lastUsername = $authenticationUtils->getLastUsername();
        
        $login = function($lastUsername,$errorIsValid,$error){
            return $this->render('security/login.html.twig', [
                'last_username' => $lastUsername,
                'errorIsValid' => $errorIsValid,
                'error' => $error,
            ]);
        };

        if($error){
            return $login($lastUsername,$errorIsValid,$error);
        }

        if($this->getUser() === null ){
            
            return $login($lastUsername,$errorIsValid,$error);
            
        }

        if($this->getUser()->isVerified() === false){

            $errorIsValid->status = true;
            $errorIsValid->data = "Verify e-mail address!";
            
            return $login($lastUsername,$errorIsValid,$error);

        }

        return $this->redirectToRoute('app_main_homepage', [], Response::HTTP_SEE_OTHER);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
