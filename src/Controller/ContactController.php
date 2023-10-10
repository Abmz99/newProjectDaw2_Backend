<?php
// src/Controller/ContactController.php
namespace App\Controller;

use App\Service\ContactService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class ContactController extends AbstractController
{
    public function index(ContactService $ContactService)
    {
        $ContactText = $ContactService->getContactText();

        return $this->render('Contact/index.html.twig', [
            'ContactText' => $ContactText,
        ]);
    }
}
?>