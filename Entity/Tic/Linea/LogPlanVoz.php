<?php

namespace App\Entity\Tic\Linea;

use App\Entity\Traits\IdTrait;
use App\Entity\Traits\TimeStampableTrait;
use App\Repository\Tic\Linea\LogPlanVozRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="nexus.tic_lineas_log_planes_voz")
 * @ORM\Entity(repositoryClass=LogPlanVozRepository::class)
 */
class LogPlanVoz
{
    use IdTrait, TimeStampableTrait;

    /**
     * @ORM\ManyToOne(targetEntity=PlanVoz::class)
     * @ORM\JoinColumn(nullable=true)
     */
    private $plan;

    /**
     * @ORM\ManyToOne(targetEntity=Linea::class, inversedBy="logPlanVoz")
     */
    private $linea;

    public function getPlan(): ?PlanVoz
    {
        return $this->plan;
    }

    public function setPlan(?PlanVoz $plan): self
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
