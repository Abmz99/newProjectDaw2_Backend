<?php
// src/Controller/ObraController.php
namespace App\Controller;

use App\Entity\Obra;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class ObraController extends AbstractController
{
    private $entityManager;

    // Constructor del controlador, inyecta el EntityManager
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    // Método para obtener obras por autor, devuelve JSON
    #[Route('/obra/autor/{autor}', name: 'app_obra_by_autor', methods: ['GET'])]
    public function getByAutor(ManagerRegistry $doctrine, string $autor): JsonResponse
    {
        // Busca obras por el nombre del autor
        $obraRepository = $doctrine->getRepository(Obra::class);
        $obras = $obraRepository->findBy(['autor' => urldecode($autor)]);

        // Si no hay obras, devuelve un error
        if (!$obras) {
            return $this->json(['error' => 'No se encontraron obras para el autor proporcionado'], Response::HTTP_NOT_FOUND);
        }

        // Convierte las obras en un array y devuelve como JSON
        $obrasData = array_map(function ($obra) {
            return [
                'id' => $obra->getId(),
                'titulo' => $obra->getTitulo(),
                'genero' => $obra->getGenero(),
                'autor' => $obra->getAutor(),
            ];
        }, $obras);

        return $this->json($obrasData);
    }

    // Método para obtener una obra específica por título, devuelve JSON
    #[Route('/obra/{titulo}', name: 'app_obra_specific', methods: ['GET'])]
    public function getSpecificObra(ManagerRegistry $doctrine, string $titulo): JsonResponse
    {
        // Busca una obra específica por su título
        $obraRepository = $doctrine->getRepository(Obra::class);
        $obra = $obraRepository->findOneBy(['titulo' => $titulo]);

        // Si no se encuentra la obra, devuelve un error
        if (!$obra) {
            return $this->json(['error' => 'Obra no encontrada'], JsonResponse::HTTP_NOT_FOUND);
        }

        // Devuelve la información de la obra como JSON
        $obraData = [
            'id' => $obra->getId(),
            'titulo' => $obra->getTitulo(),
            'genero' => $obra->getGenero(),
            'autor' => $obra->getAutor(),
        ];

        return $this->json($obraData);
    }

    // Método para listar todas las obras, devuelve JSON
    #[Route('/obra', name: 'app_obra_index', methods: ['GET'])]
    public function index(ManagerRegistry $managerRegistry): JsonResponse
    {
        // Recupera todas las obras de la base de datos
        $obraRepository = $managerRegistry->getRepository(Obra::class);
        $obras = $obraRepository->findAll();

        // Convierte las obras en un array y devuelve como JSON
        $obrasData = array_map(function ($obra) {
            return [
                'id' => $obra->getId(),
                'titulo' => $obra->getTitulo(),
                'genero' => $obra->getGenero(),
                'autor' => $obra->getAutor(),
            ];
        }, $obras);

        return $this->json($obrasData);
    }

    #[Route('/obra/create', name: 'app_obra_create', methods: ['POST','GET'])]
public function create(Request $request): JsonResponse
{
    $data = json_decode($request->getContent(), true);

    $obra = new Obra();
    $obra->setTitulo($data['titulo']);
    $obra->setGenero($data['genero']);
    $obra->setAutor($data['autor']);

    $this->entityManager->persist($obra);
    $this->entityManager->flush();

    $obraData = [
        'id' => $obra->getId(),
        'titulo' => $obra->getTitulo(),
        'genero' => $obra->getGenero(),
        'autor' => $obra->getAutor()
    ];

    return $this->json([
        'message' => 'Obra creada',
        'obra' => $obraData
    ], Response::HTTP_CREATED);
}

    

    // Método para actualizar una obra existente, recibe datos por PUT y devuelve JSON
    #[Route('/obra/update/{id}', name: 'app_obra_update', methods: ['PUT','GET'])]
    public function update(Request $request, $id): JsonResponse
    {
        // Busca la obra por ID y actualiza sus datos
        $obraRepository = $this->entityManager->getRepository(Obra::class);
        $obra = $obraRepository->find($id);

        // Si no se encuentra la obra, devuelve un error
        if (!$obra) {
            return $this->json(['error' => 'Obra no encontrada'], Response::HTTP_NOT_FOUND);
        }

        // Actualiza la obra con los nuevos datos
        $requestData = json_decode($request->getContent(), true);
        $obra->setTitulo($requestData['titulo'] ?? $obra->getTitulo());
        $obra->setGenero($requestData['genero'] ?? $obra->getGenero());
        $obra->setAutor($requestData['autor'] ?? $obra->getAutor());

        // Guarda los cambios en la base de datos
        $this->entityManager->flush();

        // Devuelve una confirmación de actualización
        return $this->json(['message' => 'Obra actualizada']);
    }

    // Método para eliminar una obra, recibe el ID por DELETE y devuelve JSON
    #[Route('/obra/delete/{id}', name: 'app_obra_delete', methods: ['DELETE','GET'])]
    public function delete($id): JsonResponse
    {
        // Busca la obra por ID y la elimina si existe
        $obra = $this->entityManager->getRepository(Obra::class)->find($id);

        // Si no se encuentra la obra, devuelve un error
        if (!$obra) {
            return $this->json(['error' => 'Obra no encontrada'], Response::HTTP_NOT_FOUND);
        }

        // Elimina la obra de la base de datos
        $this->entityManager->remove($obra);
        $this->entityManager->flush();

        // Devuelve una confirmación de eliminación
        return $this->json(['message' => 'Obra eliminada']);
    }
}
