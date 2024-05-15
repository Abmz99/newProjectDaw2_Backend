<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Favoritos
 *
 * @ORM\Table(name="favoritos")
 * @ORM\Entity
 */
class Favoritos
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Usuario")
     * @ORM\JoinColumn(name="ID_usuario", referencedColumnName="ID_usuario")
     */
    private $usuario;

    /**
     * @ORM\ManyToOne(targetEntity="Obra")
     * @ORM\JoinColumn(name="ID_obra", referencedColumnName="ID")
     */
    private $obra;

    // Getters
    public function getId(): ?int
    {
        return $this->id;
    }

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