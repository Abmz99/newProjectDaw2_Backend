<?php

namespace App\Entity;


use Doctrine\ORM\Mapping as ORM;

/**
 * Capitulos
 *
 * @ORM\Table(name="capitulos", indexes={@ORM\Index(name="ID_obra", columns={"ID_obra"})})
 * @ORM\Entity
 */
class Capitulos 
{
    /**
     * @ORM\Column(name="ID_capitulo", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idCapitulo;

    /**
     * @ORM\Column(name="num_capitulo", type="integer", nullable=true, options={"default"=NULL})
     */
    private $numCapitulo;

   /**
 * @ORM\Column(name="titulo_capitulo", type="string", length=255, nullable=true, options={"default"="NULL"})
 */
private $tituloCapitulo;
/**
 * @ORM\Column(name="contenido", type="text", nullable=true, options={"default"="NULL"})
 */
private $contenido;

/**
 * @ORM\ManyToOne(targetEntity=Obra::class)
 * @ORM\JoinColumn(name="ID_obra", referencedColumnName="ID")
 */
private $idObra;

public function getIdCapitulo(): ?int
{
    return $this->idCapitulo;
}

public function setIdCapitulo(?int $idCapitulo): self
{
    $this->idCapitulo = $idCapitulo;
    return $this;
}

public function getNumCapitulo(): ?int
{
    return $this->numCapitulo;
}

public function setNumCapitulo(?int $numCapitulo): self
{
    $this->numCapitulo = $numCapitulo;
    return $this;
}

public function getTituloCapitulo(): ?string
{
    return $this->tituloCapitulo;
}

public function setTituloCapitulo(?string $tituloCapitulo): self
{
    $this->tituloCapitulo = $tituloCapitulo;
    return $this;
}

public function getContenido(): ?string
{
    return $this->contenido;
}

public function setContenido(?string $contenido): self
{
    $this->contenido = $contenido;
    return $this;
}

public function getIdObra(): ?Obra
{
    return $this->idObra;
}

public function setIdObra(?Obra $idObra): self
{
    $this->idObra = $idObra;
    return $this;
}
}
