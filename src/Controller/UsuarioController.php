<?php
namespace App\Controller;

use App\Entity\Usuario;
use App\Entity\Roles;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Repository\UsuarioRepository;


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

    // Busca el rol estándar en la base de datos por su ID
    $rolEstandar = $this->entityManager->getRepository(Roles::class)->find(2);


    if (!$rolEstandar) {
        // Si no existe el rol estándar, devuelve un error
        return $this->json(['error' => 'El rol estándar no está definido.'], Response::HTTP_BAD_REQUEST);
    }

    $usuario = new Usuario();
    $usuario->setNombre($data['nombre']);
    $usuario->setCorreo($data['correo']);
    $usuario->setPassword($passwordHasher->hashPassword($usuario, $data['password']));
    $usuario->setIdRol($rolEstandar); // Asigna el rol estándar al nuevo usuario

    $this->entityManager->persist($usuario);
    $this->entityManager->flush();

    return $this->json([
        'message' => 'Usuario registrado exitosamente.',
        'id' => $usuario->getIdUsuario(),
        'rol' => $usuario->getIdRol()->getTipoRol(), // Retorna el tipo de rol
    ], Response::HTTP_CREATED);
}




#[Route('/api/usuario/editar/{email}', name: 'api_usuario_editar', methods: ['PUT'])]
public function editar(Request $request, UserPasswordHasherInterface $passwordHasher, string $email, UsuarioRepository $userRepo): JsonResponse
{
   // Obtiene la información del usuario actual desde el frontend ID del usuario)   
    // $email = $request->headers->get('name');
    $data = json_decode($request->getContent(), true);
    
   // Encuentra el usuario por la ID proporcionada
    // $usuario = $this->entityManager->getRepository(Usuario::class)->find($email);
    $usuario = $userRepo->findByEmail($email);
    // Si no se encuentra el usuario, devuelve un error
    if (!isset($usuario)) {
        return $this->json(['message' => 'Usuario no autorizado o no encontrado.'], Response::HTTP_FORBIDDEN);
    }

    // Procesa los datos enviados y actualiza el usuario
    $data = json_decode($request->getContent(), true);

    // $user = $this->entityManager->getRepository(Usuario::class)->find();
    $usuario->setNombre($data['name'] ?? $usuario->getNombre()); 
    $usuario->setPassword($data['pswd'] ?? $usuario->getPassword());

    // Si se proporciona una contraseña, actualiza la contraseña
    if (!empty($data['password'])) {
        $usuario->setPassword($passwordHasher->hashPassword($usuario, $data['password']));
    }

    // Persiste los cambios en la base de datos
    $this->entityManager->flush();

    // Devuelve una respuesta exitosa
    return $this->json(['message' => 'Perfil actualizado con éxito.']);
}


#[Route('/api/usuario/login', name: 'api_usuario_login', methods: ['POST'])]
public function login(Request $request, UserPasswordHasherInterface $passwordHasher, JWTTokenManagerInterface $JWTManager): JsonResponse
{
    $data = json_decode($request->getContent(), true);

    // Busca al usuario por su correo electrónico
    $usuario = $this->entityManager->getRepository(Usuario::class)->findOneBy(['correo' => $data['correo']]);
    
    // Si no se encuentra el usuario o la contraseña no coincide, devuelve un error
    if (!$usuario || !$passwordHasher->isPasswordValid($usuario, $data['password'])) {
        return $this->json(['error' => 'Correo electrónico o contraseña incorrectos.'], Response::HTTP_UNAUTHORIZED);
    }

    // Comprueba el rol del usuario para determinar si es administrador
    $isAdmin = ($usuario->getIdRol()->getTipoRol() === 'Admin');

    // Genera el JWT para el usuario
    $jwt = $JWTManager->create($usuario);
   

    return $this->json([
        'message' => 'Inicio de sesión exitoso.',
        'token' => $jwt,
        'id' => $usuario->getIdUsuario(),
        'isAdmin' => $isAdmin 
    ]);
}
}
