<?php
// src/Controller/AboutController.php
namespace App\Controller;

use App\Service\AboutService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class AboutController extends AbstractController
{
    public function index(AboutService $aboutService)
    {
        $aboutText = $aboutService->getAboutText();

        return $this->render('about/index.html.twig', [
            'aboutText' => $aboutText,
        ]);
    }
}
?>