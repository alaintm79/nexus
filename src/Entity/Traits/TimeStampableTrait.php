<?php

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

trait TimeStampableTrait {

    /**
     * @var DateTime $fechaCreado
     * @Gedmo\Timestampable(on="create")
     *
     * @ORM\Column(name="fecha_creado", type="datetime", nullable=true)
     */
    private $fechaCreado;

    /**
     * @var \DateTime $fechaModificacion
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="fecha_modificacion", type="datetime", nullable=true)
     */
    private $fechaModificacion;

    public function getFechaCreado(): ?\DateTimeInterface
    {
        return $this->fechaCreado;
    }

    // public function setFechaCreado(\DateTimeInterface $fechaCreado): self
    // {
    //     $this->fechaCreado = $fechaCreado;

    //     return $this;
    // }

    public function getFechaModificacion(): ?\DateTimeInterface
    {
        return $this->fechaModificacion;
    }

    // public function setFechaModificacion(?\DateTimeInterface $fechaModificacion): self
    // {
    //     $this->fechaModificacion = $fechaModificacion;

    //     return $this;
    // }

}
