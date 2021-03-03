<?php

namespace App\Entity\Logistica\Pago;

use App\Entity\Traits\IdTrait;
use App\Repository\Logistica\Pago\InstrumentoRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="nexus.sp_instrumentos_pagos")
 * @ORM\Entity(repositoryClass=InstrumentoRepository::class)
 */
class Instrumento
{
    use IdTrait;

    /**
     * @var string
     *
     * @ORM\Column(name="instrumento", type="string", length=50, unique=true)
     */
    private $instrumento;

    public function getInstrumento(): ?string
    {
        return $this->instrumento;
    }

    public function setInstrumento(string $instrumento): self
    {
        $this->instrumento = $instrumento;

        return $this;
    }

    /*
     *  __toString
     */
    public function __toString ()
    {
        return $this->instrumento;
    }
}
