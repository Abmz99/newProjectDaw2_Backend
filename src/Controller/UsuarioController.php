<?php
namespace App\Controller;

use App\Entity\Usuario;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UsuarioController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/api/usuario/registro', name: 'api_usuario_registro', methods: ['POST'])]
    public function registro(Request $request, UserPasswordHasherInterface $passwordHasher): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $usuario = new Usuario();
        $usuario->setNombre($data['nombre']);
        $usuario->setCorreo($data['correo']);
        $usuario->setPassword($passwordHasher->hashPassword($usuario, $data['password']));

        $this->entityManager->persist($usuario);
        $this->entityManager->flush();

        return $this->json(['message' => 'Usuario registrado exitosamente.', 'id' => $usuario->getIdUsuario()], Response::HTTP_CREATED);
    }

  
    #[Route('/api/usuario/editar/{id}', name: 'api_usuario_editar', methods: ['PUT'])]
    public function editar(Request $request, UserPasswordHasherInterface $passwordHasher, int $id): JsonResponse
    {
        // Aquí asumimos que solo los administradores pueden editar usuarios
        // O puedes comprobar si el usuario autenticado tiene permisos para editar el usuario específico
        $usuario = $this->entityManager->getRepository(Usuario::class)->find($id);
        if (!$usuario) {
            return $this->json(['message' => 'Usuario no encontrado.'], Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);

        $usuario->setNombre($data['nombre']);
        $usuario->setCorreo($data['correo']);
        if (!empty($data['password'])) {
            $usuario->setPassword($passwordHasher->hashPassword($usuario, $data['password']));
        }

        $this->entityManager->flush();

        return $this->json(['message' => 'Usuario actualizado exitosamente.']);
    }

    #[Route('/api/usuario/eliminar/{id}', name: 'api_usuario_eliminar', methods: ['DELETE'])]
    public function eliminar(int $id): JsonResponse
    {
        // Aquí asumimos que solo los administradores pueden eliminar usuarios
        $usuario = $this->entityManager->getRepository(Usuario::class)->find($id);
        if (!$usuario) {
            return $this->json(['message' => 'Usuario no encontrado.'], Response::HTTP_NOT_FOUND);
        }

        $this->entityManager->remove($usuario);
        $this->entityManager->flush();

        return $this->json(['message' => 'Usuario eliminado exitosamente.']);
    }

    #[Route('/api/usuario/login', name: 'api_usuario_login', methods: ['POST'])]
public function login(Request $request, UserPasswordHasherInterface $passwordHasher): JsonResponse
{
    $data = json_decode($request->getContent(), true);

    // Busca al usuario por su correo electrónico
    $usuario = $this->entityManager->getRepository(Usuario::class)->findOneBy(['correo' => $data['correo']]);
    
    // Si no se encuentra el usuario o la contraseña no coincide, devuelve un error
    if (!$usuario || !$passwordHasher->isPasswordValid($usuario, $data['password'])) {
        return $this->json(['error' => 'Correo electrónico o contraseña incorrectos.'], Response::HTTP_UNAUTHORIZED);
    }

    // token para mantener el estado de la autenticación
    // de momento devolvera una confirmacion
    return $this->json([
        'message' => 'Inicio de sesión exitoso.',
        'id' => $usuario->getIdUsuario(),
        // 'token' => 'un_token_de_sesión_generado'
    ]);
}


}