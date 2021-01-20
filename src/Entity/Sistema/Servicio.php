<?php

namespace App\Entity\Sistema;

use App\Entity\Traits\IdTrait;
use App\Repository\Sistema\ServicioRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="nexus.nx_servicios")
 * @ORM\Entity(repositoryClass=ServicioRepository::class)
 */
class Servicio
{
    use IdTrait;

    /**
     * @ORM\Column(type="string", length=25)
     */
    private $servicio;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $descripcion;

    public function getServicio(): ?string
    {
        return $this->servicio;
    }

    public function setServicio(string $servicio): self
    {
        $this->servicio = $servicio;

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

    /*
     *  __toString
     */

    public function __toString ()
    {
        return $this->servicio;
    }
}
