<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="obra_contenido")
 */
class ObraContenido
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $idObraContenido;

    /**
     * @ORM\ManyToOne(targetEntity="Obra")
     * @ORM\JoinColumn(name="ID_obra", referencedColumnName="ID")
     */
    private $obra; // Cambio de $idObra a $obra para mejorar la claridad

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $descripcion;

    // Getters y Setters

    public function getIdObraContenido(): ?int
    {
        return $this->idObraContenido;
    }

    public function getObra(): ?Obra
    {
        return $this->obra;
    }

    public function setObra(?Obra $obra): self
    {
        $this->obra = $obra;
        return $this;
    }

    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function setDescripcion(?string $descripcion): self
    {
        $this->descripcion = $descripcion;
        return $this;
    }
}
