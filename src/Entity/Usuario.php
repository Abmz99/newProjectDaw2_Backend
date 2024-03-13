<?php
namespace App\Entity;

use App\Repository\UsuarioRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
* @ORM\Entity(repositoryClass=UsuarioRepository::class)
* @ORM\Table(name="usuario")
*/
class Usuario implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="ID_usuario", type="integer")
     */
    private ?int $ID_usuario = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $nombre = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $correo = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $pswd = null;

    /**
     * @ORM\ManyToOne(targetEntity=Roles::class)
     * @ORM\JoinColumn(name="ID_rol", referencedColumnName="ID_rol")
     */
    private ?Roles $ID_rol = null;

    public function getIDUsuario(): ?int
    {
        return $this->ID_usuario;
    }
    public function setIDUsuario(?int $ID_usuario): self
    {
        $this->ID_usuario = $ID_usuario;
        return $this;
    }
    public function getNombre(): ?string
    {
        return $this->nombre;
    }
    public function setNombre(?string $nombre): self
    {
        $this->nombre = $nombre;
        return $this;
    }
    public function getCorreo(): ?string
    {
        return $this->correo;
    }
    public function setCorreo(?string $correo): self
    {
        $this->correo = $correo;
        return $this;
    }
    public function getPassword(): ?string
    {
        return $this->pswd;
    }
    public function setPassword(?string $pswd): self
    {
        $this->pswd = $pswd;
        return $this;
    }
    public function getIdRol(): ?Roles
    {
        return $this->ID_rol;
    }
    public function setIdRol(?Roles $ID_rol): self
    {
        $this->ID_rol = $ID_rol;
        return $this;
    }
    public function getUsername(): string
    {
        return (string) $this->correo;
    }
    public function getRoles(): array
    {
        return ['ROLE_USER'];
    }
    public function getSalt(): ?string
    {
        return null;
    }
    public function eraseCredentials(): void
    {
        // no es necesario implementar nada aquí, ya que Symfony se encarga de las credenciales
    }
    public function getUserIdentifier(): string
    {
        return (string) $this->correo;
    }
}