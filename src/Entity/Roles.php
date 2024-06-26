<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * Roles
 *
 * @ORM\Table(name="roles")
 * @ORM\Entity
 */
class Roles
{
    /**
     * @var int
     *
     * @ORM\Column(name="ID_rol", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idRol;

    /**
     * @var string
     *
     * @ORM\Column(name="tipo_rol", type="string", length=255, nullable=false)
     */
    private $tipoRol;

    public function getIdRol(): ?int
    {
        return $this->idRol;
    }

    public function getTipoRol(): ?string
    {
        return $this->tipoRol;
    }

    public function setTipoRol(string $tipoRol): static
    {
        $this->tipoRol = $tipoRol;

        return $this;
    }


}
