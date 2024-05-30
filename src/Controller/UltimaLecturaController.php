<?php

namespace App\Controller;

use App\Entity\UltimaLectura;
use App\Entity\Obra;
use App\Entity\Capitulos;
use App\Repository\UltimaLecturaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UltimaLecturaController extends AbstractController
{
    private $entityManager;
    private $ultimaLecturaRepository;

    public function __construct(EntityManagerInterface $entityManager, UltimaLecturaRepository $ultimaLecturaRepository)
    {
        $this->entityManager = $entityManager;
        $this->ultimaLecturaRepository = $ultimaLecturaRepository;
    }

    #[Route('/api/ultima_lectura', name: 'guardar_ultima_lectura', methods: ['POST'])]
    public function guardarUltimaLectura(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $usuario = $this->getUser(); // Obtener el usuario actualmente autenticado
        $obraId = $data['obraId'] ?? null;
        $capituloId = $data['capituloId'] ?? null;

        if (!$usuario || !$obraId || !$capituloId) {
            return new JsonResponse(['error' => 'Datos incompletos'], JsonResponse::HTTP_BAD_REQUEST);
        }

        // Buscar la obra y el capítulo
        $obra = $this->entityManager->getRepository(Obra::class)->find($obraId);
        $capitulo = $this->entityManager->getRepository(Capitulos::class)->find($capituloId);

        if (!$obra || !$capitulo) {
            return new JsonResponse(['error' => 'Obra o capítulo no encontrados'], JsonResponse::HTTP_NOT_FOUND);
        }

        // Buscar si ya existe una última lectura para este usuario y esta obra
        $ultimaLectura = $this->ultimaLecturaRepository->findOneBy(['usuario' => $usuario, 'obra' => $obra]);

        if (!$ultimaLectura) {
            $ultimaLectura = new UltimaLectura();
            $ultimaLectura->setUsuario($usuario);
            $ultimaLectura->setObra($obra);
        }

        $ultimaLectura->setCapitulo($capitulo);

        $this->entityManager->persist($ultimaLectura);
        $this->entityManager->flush();

        return new JsonResponse(['status' => 'Última lectura guardada']);
    }

    #[Route('/api/ultima_lectura/{obraId}', name: 'obtener_ultima_lectura', methods: ['GET'])]
    public function obtenerUltimaLectura(int $obraId): JsonResponse
    {
        $usuario = $this->getUser(); // Obtener el usuario actualmente autenticado

        if (!$usuario) {
            return new JsonResponse(['error' => 'Usuario no autenticado'], JsonResponse::HTTP_UNAUTHORIZED);
        }

        $ultimaLectura = $this->ultimaLecturaRepository->findOneBy(['usuario' => $usuario, 'obra' => $obraId]);

        if (!$ultimaLectura) {
            return new JsonResponse(['capituloId' => null]);
        }

        return new JsonResponse(['capituloId' => $ultimaLectura->getCapitulo()->getIdCapitulo()]);
    }
}
