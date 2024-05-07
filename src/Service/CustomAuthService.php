<?php
 
namespace App\Service;
 
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use App\Entity\Usuario;
 
class CustomAuthService implements AuthenticationSuccessHandlerInterface
{
    private $entityManager;
    private $passwordEncoder;
    private $JWTManager;
 
    public function __construct(
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordEncoder,
        JWTTokenManagerInterface $JWTManager
    ) {
        $this->entityManager = $entityManager;
        $this->passwordEncoder = $passwordEncoder;
        $this->JWTManager = $JWTManager;
    }
 
    public function onAuthenticationSuccess(Request $request, TokenInterface $token): Response
    {
        // Decodificar los datos del cuerpo de la solicitud JSON
        $data = json_decode($request->getContent(), true);
 
        // Buscar al usuario por su correo electrónico
        $usuario = $this->entityManager->getRepository(Usuario::class)->findOneBy(['correo' => $data['correo']]);
 
        // Si no se encuentra el usuario o la contraseña no coincide, devuelve un error
        if (!$usuario || !$this->passwordEncoder->isPasswordValid($usuario, $data['password'])) {
            return new Response(json_encode(['error' => 'Correo electrónico o contraseña incorrectos.']), Response::HTTP_UNAUTHORIZED);
        }
 
        // Comprobar el rol del usuario para determinar si es administrador
        $isAdmin = ($usuario->getIdRol()->getTipoRol() === 'Admin');
 
        // Generar el JWT para el usuario
        $jwt = $this->JWTManager->create($usuario);
 
        // Crear una instancia de Response con un mensaje de éxito y el token JWT
        $response = new Response(json_encode([
            'message' => 'Inicio de sesión exitoso.',
            'token' => $jwt,
            'id' => $usuario->getIDUsuario(),
            'isAdmin' => $isAdmin,
            'email' => $data['correo'],
            'nombre' => $usuario->getNombre()
 
        ]), Response::HTTP_OK);
 
        // Establecer las cabeceras de la respuesta
        $response->headers->set('Content-Type', 'application/json');
 
        return $response;
    }
}