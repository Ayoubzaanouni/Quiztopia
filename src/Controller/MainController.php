<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{

    #[Route('/about', name: 'app_about')]
    public function index(): Response
    {
        return $this->render('about/about.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }
    #[Route('/', name: 'app_main_2')]
    public function index2(): Response
    {
        return $this->render('welcome/welcome.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }
}
