<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UltimaLecturaRepository")
 */
class UltimaLectura
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Usuario")
     * @ORM\JoinColumn(nullable=false)
     */
    private $usuario;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Obra")
     * @ORM\JoinColumn(nullable=false)
     */
    private $obra;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Capitulos")
     * @ORM\JoinColumn(nullable=false)
     */
    private $capitulo;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsuario(): ?Usuario
    {
        return $this->usuario;
    }

    public function setUsuario(?Usuario $usuario): self
    {
        $this->usuario = $usuario;

        return $this;
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

    public function getCapitulo(): ?Capitulos
    {
        return $this->capitulo;
    }

    public function setCapitulo(?Capitulos $capitulo): self
    {
        $this->capitulo = $capitulo;

        return $this;
    }
}
