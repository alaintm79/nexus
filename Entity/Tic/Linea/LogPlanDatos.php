<?php

namespace App\Entity\Tic\Linea;

use App\Entity\Traits\IdTrait;
use App\Entity\Traits\TimeStampableTrait;
use App\Repository\Tic\Linea\LogPlanDatosRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="nexus.tic_lineas_log_planes_datos")
 * @ORM\Entity(repositoryClass=LogPlanDatosRepository::class)
 */
class LogPlanDatos
{
    use IdTrait, TimeStampableTrait;

    /**
     * @ORM\ManyToOne(targetEntity=PlanDatos::class)
     * @ORM\JoinColumn(nullable=true)
     */
    private $plan;

    /**
     * @ORM\ManyToOne(targetEntity=Linea::class, inversedBy="logPlanDatos")
     */
    private $linea;

    public function getPlan(): ?PlanDatos
    {
        return $this->plan;
    }

    public function setPlan(?PlanDatos $plan): self
    {
        $this->plan = $plan;

        return $this;
    }

    public function getLinea(): ?Linea
    {
        return $this->linea;
    }

    public function setLinea(?Linea $linea): self
    {
        $this->linea = $linea;

        return $this;
    }
}
