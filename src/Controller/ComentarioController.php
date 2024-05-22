<?php
 
namespace App\Controller;

use App\Entity\Comentarios;
use App\Entity\Obra;
use App\Entity\Usuario;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ComentariosRepository;
 
class ComentarioController extends AbstractController
{
    private EntityManagerInterface $entityManager;
 
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
 
    #[Route('/api/comentarios', name: 'crear_comentario', methods: ['POST'])]
    public function crearComentario(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
   
        // Verifica si se proporciona el ID de la obra y el texto del comentario
        if (!isset($data['idObra'], $data['texto'])) {
            return $this->json(['error' => 'Datos incompletos'], Response::HTTP_BAD_REQUEST);
        }
   
        // Busca la obra por su ID
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
   
        // Crear el comentario con la información proporcionada
        $comentario = new Comentarios();
        $comentario->setUsuario($usuario);
        $comentario->setObra($obra); // Establece la relación con la obra
        $comentario->setTexto($data['texto']);
        $comentario->setFecha(new \DateTime());
   
        // Persistir el comentario en la base de datos
        $this->entityManager->persist($comentario);
        $this->entityManager->flush();
   
        // Devolver el ID de la obra, el ID del comentario y el mensaje del comentario
        return $this->json([
            'obraId' => $obra->getId(),
            'comentarioId' => $comentario->getId(),
            'mensaje' => $comentario->getTexto()
        ]);
    }
 



    #[Route('/api/comentarios/obra/{idObra}', name: 'obtener_comentarios', methods: ['GET'])]
    public function obtenerComentarios($idObra, ComentariosRepository $comentariosRepository): JsonResponse
    {
        $comentarios = $comentariosRepository->findBy(['obra' => $idObra]);
 
        $comentariosData = array_map(function ($comentario) {
            return [
                'usuario' => $comentario->getUsuario()->getNombre(),
                'texto' => $comentario->getTexto(),
                'fecha' => $comentario->getFecha()->format('Y-m-d H:i:s')
            ];
        }, $comentarios);
 
        return new JsonResponse($comentariosData);
    }
}