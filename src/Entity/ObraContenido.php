<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ObraContenido
 *
 * @ORM\Table(name="obra_contenido", indexes={@ORM\Index(name="ID_obra", columns={"ID_obra"})})
 * @ORM\Entity
 */
class ObraContenido
{
    /**
     * @var int
     *
     * @ORM\Column(name="ID_obra_contenido", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idObraContenido;

    /**
     * @var string|null
     *
     * @ORM\Column(name="nombre_libro", type="string", length=255, nullable=true, options={"default"="NULL"})
     */
    private $nombreLibro = 'NULL';

    /**
     * @var int|null
     *
     * @ORM\Column(name="capitulo", type="integer", nullable=true, options={"default"="NULL"})
     */
    private $capitulo = NULL;

    /**
     * @var string|null
     *
     * @ORM\Column(name="contenido", type="text", length=65535, nullable=true, options={"default"="NULL"})
     */
    private $contenido = 'NULL';

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
