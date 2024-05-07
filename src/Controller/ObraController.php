<?php
// src/Controller/ObraController.php
namespace App\Controller;

use App\Entity\Obra;
use App\Repository\ObraGenerosRepository;
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

    


    #[Route('/obra/{id}', name: 'app_obra_by_id', methods: ['GET'])]
    public function getObraById(ManagerRegistry $doctrine, int $id): JsonResponse
    {          
        $obraRepository = $doctrine->getRepository(Obra::class);   
          

        $obra = $obraRepository->findOneBy(['id' => $id]);

     
        if (!$obra) {
            return new JsonResponse(['error' => 'Obra no encontrada'], Response::HTTP_NOT_FOUND);
        }
 
        $obraData = [
            'id' => $obra->getId(),
            'titulo' => $obra->getTitulo(),
            'descripcion' => $obra->getDescripcion(),
            'autor' => $obra->getAutor(),
            'rutaImagen' => $obra->getRutaImagen(),
        ];

        return $this->json($obraData);
    }




    #[Route('/obra/{id}/generos', name: 'app_obra_generos', methods: ['GET'])]
    public function getGenerosByObraId(int $id, ObraGenerosRepository $obraGenerosRepository): JsonResponse
    {
        // Obtener los géneros asociados a la obra por su ID utilizando el repositorio
        $generos = $obraGenerosRepository->findGeneroByObraId($id);
    
        // Formatear los datos de los géneros
        $formattedGeneros = [];
        foreach ($generos as $genero) {
            $formattedGeneros[] = [
                'id' => $genero['id'],
                'nombre' => $genero['nombre'],
            ];
        }
    
        // Retornar la respuesta JSON con los géneros asociados a la obra
        return $this->json($formattedGeneros);
    }
    




    #[Route('/generos/{id}', name: 'app_obras_by_genero', methods: ['GET'])]
    public function getObrasByGeneroId(int $id, ObraGenerosRepository $obraGenerosRepository): Response
    {
        // Obtener las obras relacionadas con el género por su ID utilizando el repositorio
        $obras = $obraGenerosRepository->findObrasByGeneroId($id);
    
        // Formatear los datos de las obras
        $formattedObras = [];
        foreach ($obras as $obra) {
            $formattedObras[] = [
                'id' => $obra['id'],
                'titulo' => $obra['titulo'],
                'descripcion' => $obra['descripcion'],
                'autor' => $obra['autor'],
                'rutaImagen' => $obra['rutaImagen'],
            ];
        }
    
        // Retornar la respuesta JSON con las obras asociadas al género
        return $this->json($formattedObras);
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
            'descripcion' => $obra->getDescripcion(), // Nueva línea para obtener la descripción
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
    $obra->setDescripcion($data['descripcion']);
    $obra->setAutor($data['autor']);

    $this->entityManager->persist($obra);
    $this->entityManager->flush();

    $obraData = [
        'id' => $obra->getId(),
        'titulo' => $obra->getTitulo(),
        'descripcion' => $obra->getDescripcion(),
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
