<?php

namespace App\Entity\Tic\Linea;

use App\Entity\Sistema\Usuario;
use App\Entity\Traits\IdTrait;
use App\Entity\Traits\ObservacionTrait;
use App\Entity\Traits\TimeStampableTrait;
use App\Repository\Tic\Linea\PlanExtraRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Table(name="nexus.tic_lineas_planes_extras")
 * @ORM\Entity(repositoryClass=PlanExtraRepository::class)
 */
class PlanExtra
{
    use IdTrait, TimeStampableTrait, ObservacionTrait;

    /**
     * @ORM\ManyToOne(targetEntity=Linea::class, inversedBy="planExtras")
     * @ORM\JoinColumn(nullable=false)
     */
    private $linea;

    /**
     * @ORM\ManyToOne(targetEntity=Usuario::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $usuario;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     * @Assert\Type(type="numeric")
     */
    private $montoMinutos;

    public function getLinea(): ?Linea
    {
        return $this->linea;
    }

    public function setLinea(?Linea $linea): self
    {
        $this->linea = $linea;

        return $this;
    }

    public function getUsuario(): ?Usuario
    {
        return $this->usuario;
    }

    public function setUsuario(?Usuario $usuario): self
    {
        $this->usuario = $usuario;

        return $this;
    }

    public function getMontoMinutos(): ?string
    {
        return $this->montoMinutos;
    }

    public function setMontoMinutos(string $montoMinutos): self
    {
        $this->montoMinutos = $montoMinutos;

        return $this;
    }
}
