<?php

// CapituloController.php
namespace App\Controller;
use App\Entity\Obra;
use App\Entity\Capitulos;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class CapituloController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/api/capitulos/{id}', name: 'api_capitulos_show', methods: ['GET'])]
    public function show(int $id): JsonResponse
    {
        $capitulo = $this->entityManager->getRepository(Capitulos::class)->find($id);

        if (!$capitulo) {
            return $this->json(['message' => 'Capítulo no encontrado.'], JsonResponse::HTTP_NOT_FOUND);
        }

        return $this->json([
            'id' => $capitulo->getIdCapitulo(),
            'numero' => $capitulo->getNumCapitulo(),
            'titulo' => $capitulo->getTituloCapitulo(),
            'contenido' => $capitulo->getContenido(),
        ]);
    }
}