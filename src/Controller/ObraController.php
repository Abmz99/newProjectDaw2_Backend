<?php
// src/Controller/ObraController.php
namespace App\Controller;

use App\Entity\Obra;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class ObraController extends AbstractController
{
    public function getSpecificObra(ManagerRegistry $doctrine, string $titulo): Response
    {
        // Obtiene el repositorio de la entidad Obra
        $obraRepository = $doctrine->getRepository(Obra::class);

        // Busca una obra específica por su título
        $obra = $obraRepository->findOneBy(['titulo' => $titulo]);

        // Renderiza la vista pasando la obra específica
        return $this->render('obra/index.html.twig', [
            'obra' => $obra,
        ]);
    }
}
