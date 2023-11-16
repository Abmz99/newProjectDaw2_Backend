<?php
// src/Controller/ContactController.php
namespace App\Controller;

use App\Entity\Message;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class ContactController extends AbstractController
{
    public function getContactText(ManagerRegistry $doctrine): Response
    {
        // Obtiene el repositorio de la entidad Message
        $messageRepository = $doctrine->getRepository(Message::class);

        // Busca el mensaje específico para 'contact'
        $contactMessage = $messageRepository->findOneBy(['codeMessage' => 'contact']);

        // Renderiza la vista pasando solo el mensaje de 'contact'
        return $this->render('contact/index.html.twig', [
            'controller_name' => 'MessageController',
            'contactMessage' => $contactMessage
        ]);
    }
}
