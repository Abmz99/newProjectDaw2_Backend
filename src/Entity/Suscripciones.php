<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Suscripciones
 *
 * @ORM\Table(name="suscripciones")
 * @ORM\Entity
 */
class Suscripciones
{
    /**
     * @var int
     *
     * @ORM\Column(name="ID_suscripcion", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idSuscripcion;

    /**
     * @var string
     *
     * @ORM\Column(name="tipo_suscripcion", type="string", length=255, nullable=false)
     */
    private $tipoSuscripcion;

    /**
     * @var string
     *
     * @ORM\Column(name="precio", type="decimal", precision=10, scale=2, nullable=false)
     */
    private $precio;


}
