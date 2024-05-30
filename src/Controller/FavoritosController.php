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
 
class FavoritosController extends AbstractController
{
    private EntityManagerInterface $entityManager;
 
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
 
    #[Route('/api/favoritos/{idUsuario}', name: 'leer_favorito', methods: ['GET'])]
    public function leer(int $idUsuario): JsonResponse
    {
        // Buscar usuario por ID
        $usuario = $this->entityManager->getRepository(Usuario::class)->find($idUsuario);
 
        if (!$usuario) {
            return $this->json(['error' => 'Usuario no encontrado'], Response::HTTP_NOT_FOUND);
        }
 
        // Buscar favoritos del usuario
        $favoritos = $this->entityManager->getRepository(Favoritos::class)->findBy(['usuario' => $usuario]);
 
        // Transformar los favoritos para incluir detalles de la obra
        $favoritosData = [];
        foreach ($favoritos as $favorito) {
            $obra = $favorito->getObra();
            $favoritosData[] = [
                'id' => $obra->getId(),
                'titulo' => $obra->getTitulo(),
                'autor' => $obra->getAutor(),
                'rutaImagen' => $obra->getRutaImagen()
            ];
        }
 
        return $this->json($favoritosData, Response::HTTP_OK);
    }
    #[Route('/api/favoritos', name: 'agregar_favorito', methods: ['POST'])]
    public function agregar(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
   
        if (!isset($data['idObra'], $data['idUsuario'])) {
            return $this->json(['error' => 'Datos incompletos'], Response::HTTP_BAD_REQUEST);
        }
   
        $obra = $this->entityManager->getRepository(Obra::class)->find($data['idObra']);
        if (!$obra) {
            return $this->json(['error' => 'Obra no encontrada'], Response::HTTP_NOT_FOUND);
        }
   
        $usuario = $this->entityManager->getRepository(Usuario::class)->find($data['idUsuario']);
        if (!$usuario) {
            return $this->json(['error' => 'Usuario no encontrado'], Response::HTTP_NOT_FOUND);
        }
   
        $favoritoExistente = $this->entityManager->getRepository(Favoritos::class)->findOneBy(['usuario' => $usuario, 'obra' => $obra]);
        if ($favoritoExistente) {
            return $this->json(['error' => 'Esta obra ya está marcada como favorita por este usuario'], Response::HTTP_BAD_REQUEST);
        }
   
        $favorito = new Favoritos();
        $favorito->setUsuario($usuario);
        $favorito->setObra($obra);
   
        $this->entityManager->persist($favorito);
        $this->entityManager->flush();
   
        return $this->json(['message' => 'Obra agregada a favoritos'], Response::HTTP_CREATED);
    }
   
}