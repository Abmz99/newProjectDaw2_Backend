<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GenerosController extends AbstractController
{
    #[Route('/generos', name: 'app_generos')]
    public function index(): Response
    {
        return $this->render('generos/index.html.twig', [
            'controller_name' => 'GenerosController',
        ]);
    }
}
