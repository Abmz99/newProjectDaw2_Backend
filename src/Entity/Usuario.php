<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

/**
 * @ORM\Entity
 * @ORM\Table(name="usuario")
 */
class Usuario implements PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $idUsuario;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nombre;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $correo;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $pswd;

    // Getters y setters...

    public function getIdUsuario(): ?int
    {
        return $this->idUsuario;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;
        return $this;
    }

    public function getCorreo(): ?string
    {
        return $this->correo;
    }

    public function setCorreo(string $correo): self
    {
        $this->correo = $correo;
        return $this;
    }

    public function getPassword(): string
    {
        return $this->pswd;
    }

    public function setPassword(string $password): self
    {
        $this->pswd = $password;
        return $this;
    }
}
