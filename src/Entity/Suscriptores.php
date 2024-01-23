<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Suscriptores
 *
 * @ORM\Table(name="suscriptores", indexes={@ORM\Index(name="ID_suscripcion", columns={"ID_suscripcion"}), @ORM\Index(name="ID_obra", columns={"ID_obra"}), @ORM\Index(name="ID_usuario", columns={"ID_usuario"})})
 * @ORM\Entity
 */
class Suscriptores
{
    /**
     * @var int
     *
     * @ORM\Column(name="ID_suscriptor", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idSuscriptor;

    /**
     * @var string|null
     *
     * @ORM\Column(name="texto", type="text", length=65535, nullable=true, options={"default"="NULL"})
     */
    private $texto = 'NULL';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha", type="datetime", nullable=false, options={"default"="current_timestamp()"})
     */
    private $fecha = 'current_timestamp()';

    /**
     * @var \Usuario
     *
     * @ORM\ManyToOne(targetEntity="Usuario")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ID_usuario", referencedColumnName="ID_usuario")
     * })
     */
    private $idUsuario;

    /**
     * @var \Suscripciones
     *
     * @ORM\ManyToOne(targetEntity="Suscripciones")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ID_suscripcion", referencedColumnName="ID_suscripcion")
     * })
     */
    private $idSuscripcion;

    /**
     * @var \Obra
     *
     * @ORM\ManyToOne(targetEntity="Obra")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ID_obra", referencedColumnName="ID")
     * })
     */
    private $idObra;


}
