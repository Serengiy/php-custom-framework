<?php

namespace App\Controllers;

use Somecode\Framework\Controller\AbstractController;
use Somecode\Framework\Http\Response;

class HomeController extends AbstractController
{
    public function index(): Response
    {
        return $this->render('home.html.twig', [
            'name' => 'Ivan',
        ]);
        //        return new Response($content);
    }

    public function dashboard()
    {
        return $this->render('dashboard.html.twig');
    }
}
