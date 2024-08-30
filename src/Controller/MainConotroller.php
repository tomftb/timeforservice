<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MainConotroller
{
    #[Route('/', name: 'app_home', methods: ['GET'])]
    public function homepage(): Response
    {
        // dd($this);
        return new Response('<p>homepage</p>', 200);
    }
}
