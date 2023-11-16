<?php
// src/Controller/AboutController.php
namespace App\Controller;

use App\Entity\Message;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class AboutController extends AbstractController
{
    public function getAboutText(ManagerRegistry $doctrine): Response
    {
        // Obtiene el repositorio de la entidad Message
        $messageRepository = $doctrine->getRepository(Message::class);

        // Busca el mensaje específico para 'about'
        $aboutMessage = $messageRepository->findOneBy(['codeMessage' => 'aboutUs']);

        // Renderiza la vista pasando solo el mensaje de 'about'
        return $this->render('about/index.html.twig', [
            'controller_name' => 'MessageController',
            'aboutMessage' => $aboutMessage
        ]);
    }
}
