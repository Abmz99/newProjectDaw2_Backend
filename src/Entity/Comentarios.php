<?php
 
namespace App\Entity;
 
use Doctrine\ORM\Mapping as ORM;
 
/**
 * Comentarios
 *
 * @ORM\Table(name="comentarios")
 * @ORM\Entity
 */
class Comentarios
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(type="integer")
     */
    private $id;
 
    /**
     * @ORM\ManyToOne(targetEntity="Usuario")
     * @ORM\JoinColumn(name="ID_usuario", referencedColumnName="ID_usuario", nullable=true)
     */
    private $usuario;
 
    /**
     * @ORM\ManyToOne(targetEntity="Obra")
     * @ORM\JoinColumn(name="ID_obra")
     */
    private $obra;
 
    /**
     * @ORM\Column(type="text")
     */
    private $texto;
 
    /**
     * @ORM\Column(type="datetime", options={"default": "CURRENT_TIMESTAMP"})
     */
    private $fecha;
 
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
 
    public function getTexto(): ?string
    {
        return $this->texto;
    }
 
    public function getFecha(): ?\DateTimeInterface
    {
        return $this->fecha;
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
 
    public function setTexto(string $texto): self
    {
        $this->texto = $texto;
        return $this;
    }
 
    public function setFecha(\DateTimeInterface $fecha): self
    {
        $this->fecha = $fecha;
        return $this;
    }
}
 