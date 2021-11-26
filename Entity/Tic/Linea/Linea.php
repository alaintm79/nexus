<?php

namespace App\Entity\Tic\Linea;

use App\Entity\Traits\IdTrait;
use App\Entity\Sistema\Usuario;
use App\Entity\Traits\BajaTrait;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Traits\ObservacionTrait;
use App\Entity\Traits\TimeStampableTrait;
use Doctrine\Common\Collections\Collection;
use App\Repository\Tic\Linea\LineaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="nexus.tic_lineas")
 * @ORM\Entity(repositoryClass=LineaRepository::class)
 * @UniqueEntity(fields={"numero"}, message="Número en uso!.")
 */
class Linea
{
    use IdTrait, TimeStampableTrait, BajaTrait, ObservacionTrait;

    /**
     * @ORM\Column(type="string", length=8, nullable=false)
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = 8,
     *      max = 8
     * )
     * @Assert\Regex(pattern="/^[0-9\/]+$/i", match=true, message="Valor no permitido")
     */
    private $numero;

    /**
     * @ORM\ManyToOne(targetEntity=PlanVoz::class, inversedBy="lineas")
     * @ORM\JoinColumn(nullable=false)
     */
    private $planVoz;

    /**
     * @ORM\ManyToOne(targetEntity=PlanDatos::class, inversedBy="lineas")
     */
    private $planDatos;

    /**
     * @ORM\OneToOne(targetEntity=PlanAdicional::class, inversedBy="linea", cascade={"persist", "remove"})
     */
    private $planAdicional;

    /**
     * @ORM\Column(type="string", length=4, nullable=true)
     */
    private $pin;

    /**
     * @ORM\Column(type="string", length=11, nullable=true)
     */
    private $puk;

    /**
     * @ORM\OneToMany(targetEntity=LogPlanVoz::class, mappedBy="linea")
     */
    private $logPlanVoz;

    /**
     * @ORM\OneToMany(targetEntity=LogPlanDatos::class, mappedBy="linea")
     */
    private $logPlanDatos;

    /**
     * @ORM\OneToMany(targetEntity=LogSim::class, mappedBy="linea")
     */
    private $logSim;

    /**
     * @ORM\OneToMany(targetEntity=LogUsuario::class, mappedBy="linea")
     */
    private $logUsuario;

    /**
     * @ORM\ManyToOne(targetEntity=Usuario::class)
     * @ORM\JoinColumn(nullable=true)
     */
    private $usuario;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isReserva;

    /**
     * @ORM\OneToMany(targetEntity=PlanExtra::class, mappedBy="linea")
     */
    private $planExtras;

    public function __construct()
    {
        $this->logPlanVoz = new ArrayCollection();
        $this->logPlanDatos = new ArrayCollection();
        $this->logSim = new ArrayCollection();
        $this->logUsuario = new ArrayCollection();
        $this->planExtras = new ArrayCollection();
    }

    public function getNumero(): ?string
    {
        return $this->numero;
    }

    public function setNumero(?string $numero): self
    {
        $this->numero = $numero;

        return $this;
    }

    public function getPlanVoz(): ?PlanVoz
    {
        return $this->planVoz;
    }

    public function setPlanVoz(?PlanVoz $planVoz): self
    {
        $this->planVoz = $planVoz;

        return $this;
    }

    public function getPlanDatos(): ?PlanDatos
    {
        return $this->planDatos;
    }

    public function setPlanDatos(?PlanDatos $planDatos): self
    {
        $this->planDatos = $planDatos;

        return $this;
    }

    public function getPlanAdicional(): ?PlanAdicional
    {
        return $this->planAdicional;
    }

    public function setPlanAdicional(?PlanAdicional $planAdicional): self
    {
        $this->planAdicional = $planAdicional;

        return $this;
    }

    public function getPin(): ?string
    {
        return $this->pin;
    }

    public function setPin(?string $pin): self
    {
        $this->pin = $pin;

        return $this;
    }

    public function getPuk(): ?string
    {
        return $this->puk;
    }

    public function setPuk(?string $puk): self
    {
        $this->puk = $puk;

        return $this;
    }

    /**
     * @return Collection|LogPlanVoz[]
     */
    public function getLogPlanVoz(): Collection
    {
        return $this->logPlanVoz;
    }

    public function addLogPlanVoz(LogPlanVoz $logPlanVoz): self
    {
        if (!$this->logPlanVoz->contains($logPlanVoz)) {
            $this->logPlanVoz[] = $logPlanVoz;
            $logPlanVoz->setLinea($this);
        }

        return $this;
    }

    public function removeLogPlanVoz(LogPlanVoz $logPlanVoz): self
    {
        if ($this->logPlanVoz->removeElement($logPlanVoz)) {
            // set the owning side to null (unless already changed)
            if ($logPlanVoz->getLinea() === $this) {
                $logPlanVoz->setLinea(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|LogPlanDatos[]
     */
    public function getLogPlanDatos(): Collection
    {
        return $this->logPlanDatos;
    }

    public function addLogPlanDato(LogPlanDatos $logPlanDato): self
    {
        if (!$this->logPlanDatos->contains($logPlanDato)) {
            $this->logPlanDatos[] = $logPlanDato;
            $logPlanDato->setLinea($this);
        }

        return $this;
    }

    public function removeLogPlanDato(LogPlanDatos $logPlanDato): self
    {
        if ($this->logPlanDatos->removeElement($logPlanDato)) {
            // set the owning side to null (unless already changed)
            if ($logPlanDato->getLinea() === $this) {
                $logPlanDato->setLinea(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|LogSim[]
     */
    public function getLogSim(): Collection
    {
        return $this->logSim;
    }

    public function addLogSim(LogSim $logSim): self
    {
        if (!$this->logSim->contains($logSim)) {
            $this->logSim[] = $logSim;
            $logSim->setLinea($this);
        }

        return $this;
    }

    public function removeLogSim(LogSim $logSim): self
    {
        if ($this->logSim->removeElement($logSim)) {
            // set the owning side to null (unless already changed)
            if ($logSim->getLinea() === $this) {
                $logSim->setLinea(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|LogUsuario[]
     */
    public function getLogUsuario(): Collection
    {
        return $this->logUsuario;
    }

    public function addLogUsuario(LogUsuario $logUsuario): self
    {
        if (!$this->logUsuario->contains($logUsuario)) {
            $this->logUsuario[] = $logUsuario;
            $logUsuario->setLinea($this);
        }

        return $this;
    }

    public function removeLogUsuario(LogUsuario $logUsuario): self
    {
        if ($this->logUsuario->removeElement($logUsuario)) {
            // set the owning side to null (unless already changed)
            if ($logUsuario->getLinea() === $this) {
                $logUsuario->setLinea(null);
            }
        }

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

    public function getIsReserva(): ?bool
    {
        return $this->isReserva;
    }

    public function setIsReserva(?bool $isReserva): self
    {
        $this->isReserva = $isReserva;

        return $this;
    }

    /**
     * @return Collection|PlanExtra[]
     */
    public function getPlanExtras(): Collection
    {
        return $this->planExtras;
    }

    public function addPlanExtra(PlanExtra $planExtra): self
    {
        if (!$this->planExtras->contains($planExtra)) {
            $this->planExtras[] = $planExtra;
            $planExtra->setLinea($this);
        }

        return $this;
    }

    public function removePlanExtra(PlanExtra $planExtra): self
    {
        if ($this->planExtras->removeElement($planExtra)) {
            // set the owning side to null (unless already changed)
            if ($planExtra->getLinea() === $this) {
                $planExtra->setLinea(null);
            }
        }

        return $this;
    }
}
