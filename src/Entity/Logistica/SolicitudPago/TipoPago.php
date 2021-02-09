<?php

namespace App\Entity\Logistica\SolicitudPago;

use App\Entity\Traits\IdTrait;
use App\Repository\Logistica\SolicitudPago\TipoPagoRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="nexus.sp_tipos_pagos")
 * @ORM\Entity(repositoryClass=TipoPagoRepository::class)
 */
class TipoPago
{
    use IdTrait;

    /**
     * @var string
     *
     * @ORM\Column(name="tipo", type="string", length=50, unique=true)
     */
    private $tipo;

    public function getTipo(): ?string
    {
        return $this->tipo;
    }

    public function setTipo(string $tipo): self
    {
        $this->tipo = $tipo;

        return $this;
    }

    /*
     *  __toString
     */
    public function __toString ()
    {
        return $this->tipo;
    }
}
