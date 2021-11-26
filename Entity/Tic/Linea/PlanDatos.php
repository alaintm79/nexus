<?php

namespace App\Entity\Tic\Linea;

use App\Entity\Traits\IdTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\Tic\Linea\PlanDatosRepository;

/**
 * @ORM\Table(name="nexus.tic_lineas_planes_datos")
 * @ORM\Entity(repositoryClass=PlanDatosRepository::class)
 */
class PlanDatos
{
    use IdTrait;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $plan;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     */
    private $rentaMensual;

    /**
     * @ORM\Column(type="integer")
     */
    private $mbIncluidos;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     */
    private $tarifaMbAdicional;

    /**
     * @ORM\OneToMany(targetEntity=Linea::class, mappedBy="planDatos")
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

    public function getRentaMensual(): ?string
    {
        return $this->rentaMensual;
    }

    public function setRentaMensual(string $rentaMensual): self
    {
        $this->rentaMensual = $rentaMensual;

        return $this;
    }

    public function getMbIncluidos(): ?int
    {
        return $this->mbIncluidos;
    }

    public function setMbIncluidos(int $mbIncluidos): self
    {
        $this->mbIncluidos = $mbIncluidos;

        return $this;
    }

    public function getTarifaMbAdicional(): ?string
    {
        return $this->tarifaMbAdicional;
    }

    public function setTarifaMbAdicional(string $tarifaMbAdicional): self
    {
        $this->tarifaMbAdicional = $tarifaMbAdicional;

        return $this;
    }

    /*
     *  __toString
     */
    public function __toString ()
    {
        return $this->plan;
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
            $linea->setPlanDatos($this);
        }

        return $this;
    }

    public function removeLinea(Linea $linea): self
    {
        if ($this->lineas->removeElement($linea)) {
            // set the owning side to null (unless already changed)
            if ($linea->getPlanDatos() === $this) {
                $linea->setPlanDatos(null);
            }
        }

        return $this;
    }
}
