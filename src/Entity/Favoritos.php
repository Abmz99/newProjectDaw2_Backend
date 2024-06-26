<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="favoritos")
 * @ORM\Entity
 */
class Favoritos
{
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="App\Entity\Usuario")
     * @ORM\JoinColumn(name="ID_usuario", referencedColumnName="ID_usuario")
     */
    private $usuario;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="App\Entity\Obra")
     * @ORM\JoinColumn(name="ID_obra", referencedColumnName="id")
     */
    private $obra;

    // Getters
    public function getUsuario(): ?Usuario
    {
        return $this->usuario;
    }

    public function getObra(): ?Obra
    {
        return $this->obra;
    }

    // Setters
    public function setUsuario(?Usuario $usuario): self
    {
        $this->usuario = $usuario;
        return $this;
    }

    public function setObra(?Obra $obra): self
    {
        $this->obra = $obra;
        return $this;
    }
}
