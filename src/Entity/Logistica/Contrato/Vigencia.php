<?php

namespace App\Entity\Logistica\Contrato;

use App\Entity\Traits\IdTrait;
use App\Repository\Logistica\Contrato\VigenciaRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="nexus.cto_vigencias")
 * @ORM\Entity(repositoryClass=VigenciaRepository::class)
 */
class Vigencia
{
    use IdTrait;

    /**
     * @var string
     *
     * @ORM\Column(name="vigencia", type="string", length=255)
     */
    private $vigencia;

    /**
     * @var int
     *
     * @ORM\Column(name="orden", type="integer")
     */
    private $orden;

    public function getVigencia(): ?string
    {
        return $this->vigencia;
    }

    public function setVigencia(string $vigencia): self
    {
        $this->vigencia = $vigencia;

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
        return $this->vigencia;
    }
}
