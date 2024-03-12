<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class CapituloController extends AbstractController
{
    private $entityManager;

    // Constructor del controlador, inyecta el EntityManager
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/api/capitulos/{id}', name: 'api_get_capitulo', methods: ['GET'])]
    public function getCapitulo($id): Response
    {
        $capitulo = $this->entityManager->find($id);

        if (!$capitulo) {
            throw $this->createNotFoundException('No se encontró el capítulo con el ID: ' . $id);
        }

        // Serializar el objeto capítulo para devolverlo como respuesta
        $data = [
            'id' => $capitulo->getIdCapitulo(),
            'num_capitulo' => $capitulo->getNumCapitulo(),
            'titulo_capitulo' => $capitulo->getTituloCapitulo(),
            'contenido' => $capitulo->getContenido(),
            // Puedes añadir más campos según sea necesario
        ];

        return $this->json($data);
    }
}
