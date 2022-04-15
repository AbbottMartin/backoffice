<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route(['/home', '/'], name: 'homepage')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    #[Route('/common', name: 'common_route')]
    public function common(): Response
    {
        return $this->render('home/common.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    #[Route('/test', name: 'test_route')]
    public function test(): Response
    {
        return $this->render('home/test.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
}
