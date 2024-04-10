<?php

namespace App\Entity;
use App\Entity\Obra;
use App\Entity\Generos;
use Doctrine\ORM\Mapping as ORM;

/**
 * ObraGeneros
 *
 * @ORM\Table(name="obra_generos", indexes={@ORM\Index(name="ID", columns={"ID"}), @ORM\Index(name="ID_genero", columns={"ID_genero"})})
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="App\Repository\ObraGenerosRepository")
 */
class ObraGeneros
{
    /**
     * @var int
     *
     * @ORM\Column(name="ID_obra_genero", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idObraGenero;

    /**
     * @var \Obra
     *
     * @ORM\ManyToOne(targetEntity="Obra")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ID", referencedColumnName="ID")
     * })
     */
    private $id;

    /**
     * @var \Generos
     *
     * @ORM\ManyToOne(targetEntity="Generos")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ID_genero", referencedColumnName="ID_genero")
     * })
     */
    private $idGenero;



    /**
     * Get the value of idObraGenero
     *
     * @return  int
     */ 
    public function getIdObraGenero()
    {
        return $this->idObraGenero;
    }

    /**
     * Set the value of idObraGenero
     *
     * @param  int  $idObraGenero
     *
     * @return  self
     */ 
    public function setIdObraGenero(int $idObraGenero)
    {
        $this->idObraGenero = $idObraGenero;

        return $this;
    }

    /**
     * Get the value of idGenero
     *
     * @return  \Generos
     */ 
    public function getIdGenero()
    {
        return $this->idGenero;
    }

    /**
     * Set the value of idGenero
     *
     * @param  \Generos  $idGenero
     *
     * @return  self
     */ 
    public function setIdGenero(?Generos $idGenero)
    {
        $this->idGenero = $idGenero;

        return $this;
    }

    /**
     * Get the value of id
     *
     * @return  \Obra
     */ 
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @param  \Obra  $id
     *
     * @return  self
     */ 
    public function setId(?Obra $id)
    {
        $this->id = $id;

        return $this;
    }
}
