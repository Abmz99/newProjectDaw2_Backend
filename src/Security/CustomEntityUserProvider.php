<?php

// src/Security/CustomEntityUserProvider.php
namespace App\Security;

use App\Entity\Usuario;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class CustomEntityUserProvider implements UserProviderInterface
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        $usuario = $this->entityManager->getRepository(Usuario::class)->findOneBy(['correo' => $identifier]);

        if (!$usuario) {
            throw new UserNotFoundException(sprintf('Usuario con correo "%s" no encontrado.', $identifier));
        }

        return $usuario;
    }

    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof Usuario) {
            throw new UnsupportedUserException(sprintf('Instancias de "%s" no son soportadas.', get_class($user)));
        }

        return $this->loadUserByIdentifier($user->getUserIdentifier());
    }

    public function supportsClass(string $class)
    {
        return Usuario::class === $class;
    }
}

?>