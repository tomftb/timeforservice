<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MainConotroller extends AbstractController
{
    #[Route('/', name: 'app_home', methods: ['GET'])]
    public function homepage(): Response
    {
        // dd($this);
        $starshipCount = 457;
        
        $test = [
            'sss'=>'aaa',
            'bb'=>'yyy',
        ];
        
        return $this->render('main/homepage.html.twig',[
            'starshipCount'=>$starshipCount,
            'test'=>$test
        ]);
    }
}
