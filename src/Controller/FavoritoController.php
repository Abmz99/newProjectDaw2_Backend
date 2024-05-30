<?php

namespace App\Controller;

use App\Entity\Favoritos;
use App\Entity\Obra;
use App\Entity\Usuario;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class FavoritoController extends AbstractController
{
	private EntityManagerInterface $entityManager;

	public function __construct(EntityManagerInterface $entityManager)
	{
		$this->entityManager = $entityManager;
	}

	#[Route('/api/favoritos', name: 'agregar_favorito', methods: ['POST'])]
	public function agregar(Request $request): JsonResponse
	{
		$data = json_decode($request->getContent(), true);

		// ID de la obra está presente y es válido
		$obra = $this->entityManager->getRepository(Obra::class)->find($data['idObra']);
		if (!$obra) {
			return $this->json(['error' => 'Obra no encontrada'], Response::HTTP_NOT_FOUND);
		}

		// ID de usuario está presente y es válido
		$usuarioId = $data['usuarioId'] ?? null;
		if (!$usuarioId) {
			return $this->json(['error' => 'ID de usuario no proporcionado'], Response::HTTP_BAD_REQUEST);
		}

		// Busca el usuario por su ID
		$usuario = $this->entityManager->getRepository(Usuario::class)->find($usuarioId);
		if (!$usuario) {
			return $this->json(['error' => 'Usuario no encontrado'], Response::HTTP_NOT_FOUND);
		}

		// Verificar si ya existe un favorito para esta obra y usuario
		$favoritoExistente = $this->entityManager->getRepository(Favoritos::class)->findOneBy(['usuario' => $usuario, 'obra' => $obra]);
		if ($favoritoExistente) {
			return $this->json(['error' => 'Esta obra ya está marcada como favorita por este usuario'], Response::HTTP_BAD_REQUEST);
		}

		// Crear el favorito
		$favorito = new Favoritos();
		$favorito->setUsuario($usuario);
		$favorito->setObra($obra);

		// Persistir el favorito en la base de datos
		$this->entityManager->persist($favorito);
		$this->entityManager->flush();

		// Obtener todos los favoritos del usuario
		$favoritosUsuario = $usuario->getFavoritos();

		return $this->json([
			'message' => 'Obra agregada a favoritos',
			'favoritos' => $favoritosUsuario
		], Response::HTTP_CREATED);
	}
}
