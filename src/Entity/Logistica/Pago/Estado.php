<?php

namespace App\Entity\Logistica\Pago;

use App\Entity\Traits\IdTrait;
use App\Repository\Logistica\Pago\EstadoRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="nexus.sp_estados")
 * @ORM\Entity(repositoryClass=EstadoRepository::class)
 */
class Estado
{
    use IdTrait;

    /**
     * @var string
     *
     * @ORM\Column(name="estado", type="string", length=255)
     */
    private $estado;

    /**
     * @var int
     *
     * @ORM\Column(name="orden", type="integer")
     */
    private $orden;

    public function getEstado(): ?string
    {
        return $this->estado;
    }

    public function setEstado(string $estado): self
    {
        $this->estado = $estado;

        return $this;
    }

    public function getOrden(): ?int
    {
        return $this->orden;
    }

    public function setOrden(int $orden): self
    {
        $this->orden = $orden;

        return $this;
    }

    /*
     *  __toString
     */

    public function __toString ()
    {
        return $this->estado;
    }
}
