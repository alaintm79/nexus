<?php

namespace App\Entity\Tic\Linea;

use App\Entity\Traits\IdTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\Tic\Linea\PlanVozRepository;

/**
 * @ORM\Table(name="nexus.tic_lineas_planes_voz")
 * @ORM\Entity(repositoryClass=PlanVozRepository::class)
 */
class PlanVoz
{
    use IdTrait;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $plan;

    /**
     * @ORM\Column(type="float")
     */
    private $cuotaMensual;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $minutosIncluidos;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $smsIncluidos;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $mbIncluidos;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     */
    private $tarifaMinutoAdicional;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     */
    private $tarifaSmsAdicional;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     */
    private $tarifaMbAdicional;

    /**
     * @ORM\OneToMany(targetEntity=Linea::class, mappedBy="planVoz")
     */
    private $lineas;

    public function __construct()
    {
        $this->lineas = new ArrayCollection();
    }

    public function getPlan(): ?string
    {
        return $this->plan;
    }

    public function setPlan(string $plan): self
    {
        $this->plan = $plan;

        return $this;
    }

    public function getCuotaMensual(): ?float
    {
        return $this->cuotaMensual;
    }

    public function setCuotaMensual(float $cuotaMensual): self
    {
        $this->cuotaMensual = $cuotaMensual;

        return $this;
    }

    public function getMinutosIncluidos(): ?int
    {
        return $this->minutosIncluidos;
    }

    public function setMinutosIncluidos(?int $minutosIncluidos): self
    {
        $this->minutosIncluidos = $minutosIncluidos;

        return $this;
    }

    public function getSmsIncluidos(): ?int
    {
        return $this->smsIncluidos;
    }

    public function setSmsIncluidos(?int $smsIncluidos): self
    {
        $this->smsIncluidos = $smsIncluidos;

        return $this;
    }

    public function getMbIncluidos(): ?int
    {
        return $this->mbIncluidos;
    }

    public function setMbIncluidos(?int $mbIncluidos): self
    {
        $this->mbIncluidos = $mbIncluidos;

        return $this;
    }

    public function getTarifaMinutoAdicional(): ?float
    {
        return $this->tarifaMinutoAdicional;
    }

    public function setTarifaMinutoAdicional(?float $tarifaMinutoAdicional): self
    {
        $this->tarifaMinutoAdicional = $tarifaMinutoAdicional;

        return $this;
    }

    public function getTarifaSmsAdicional(): ?float
    {
        return $this->tarifaSmsAdicional;
    }

    public function setTarifaSmsAdicional(?float $tarifaSmsAdicional): self
    {
        $this->tarifaSmsAdicional = $tarifaSmsAdicional;

        return $this;
    }

    public function getTarifaMbAdicional(): ?float
    {
        return $this->tarifaMbAdicional;
    }

    public function setTarifaMbAdicional(?float $tarifaMbAdicional): self
    {
        $this->tarifaMbAdicional = $tarifaMbAdicional;

        return $this;
    }

    /**
     * @return Collection|Linea[]
     */
    public function getLineas(): Collection
    {
        return $this->lineas;
    }

    public function addLinea(Linea $linea): self
    {
        if (!$this->lineas->contains($linea)) {
            $this->lineas[] = $linea;
            $linea->setPlanVoz($this);
        }

        return $this;
    }

    public function removeLinea(Linea $linea): self
    {
        if ($this->lineas->removeElement($linea)) {
            // set the owning side to null (unless already changed)
            if ($linea->getPlanVoz() === $this) {
                $linea->setPlanVoz(null);
            }
        }

        return $this;
    }

    /*
     *  __toString
     */
    public function __toString ()
    {
        return $this->plan;
    }
}
