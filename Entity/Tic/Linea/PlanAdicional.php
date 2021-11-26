<?php

namespace App\Entity\Tic\Linea;

use App\Entity\Traits\IdTrait;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\Tic\Linea\PlanAdicionalRepository;

/**
 * @ORM\Table(name="nexus.tic_lineas_planes_adicionales")
 * @ORM\Entity(repositoryClass=PlanAdicionalRepository::class)
 */
class PlanAdicional
{
    use IdTrait;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     */
    private $cuotaMinutos;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     */
    private $cuotaSms;

    /**
     * @ORM\OneToOne(targetEntity=Linea::class, mappedBy="planAdicional", cascade={"persist", "remove"})
     */
    private $linea;

    public function getCuotaMinutos(): ?string
    {
        return $this->cuotaMinutos;
    }

    public function setCuotaMinutos(?string $cuotaMinutos): self
    {
        $this->cuotaMinutos = $cuotaMinutos;

        return $this;
    }

    public function getCuotaSms(): ?string
    {
        return $this->cuotaSms;
    }

    public function setCuotaSms(?string $cuotaSms): self
    {
        $this->cuotaSms = $cuotaSms;

        return $this;
    }

    public function getLinea(): ?Linea
    {
        return $this->linea;
    }

    public function setLinea(?Linea $linea): self
    {
        // unset the owning side of the relation if necessary
        if ($linea === null && $this->linea !== null) {
            $this->linea->setPlanAdicional(null);
        }

        // set the owning side of the relation if necessary
        if ($linea !== null && $linea->getPlanAdicional() !== $this) {
            $linea->setPlanAdicional($this);
        }

        $this->linea = $linea;

        return $this;
    }
}
