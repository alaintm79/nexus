<?php

namespace App\Entity\Logistica\Contrato;

use App\Entity\Logistica\Pago\Solicitud;
use App\Entity\Traits\IdTrait;
use App\Entity\Traits\TimeStampableTrait;
use App\Repository\Logistica\Contrato\EjecucionRepository;
use Doctrine\ORM\Mapping as ORM;


/**
 * Ejecucion
 *
 * @ORM\Table(name="nexus.cto_ejecuciones")
 * @ORM\Entity(repositoryClass=EjecucionRepository::class)
 */
class Ejecucion
{
    use IdTrait, TimeStampableTrait;

    /**
     * @ORM\OneToOne(targetEntity=Solicitud::class)
     * @ORM\JoinColumn(name="solicitud_id", referencedColumnName="id")
     */
    private $solicitud;

    /**
     * @var float
     *
     * @ORM\Column(name="saldo_cup", type="float", nullable=true)
     */
    private $saldoCup;

    /**
     * @var float
     *
     * @ORM\Column(name="saldo_cuc", type="float", nullable=true)
     */
    private $saldoCuc;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSaldoCup(): ?float
    {
        return $this->saldoCup;
    }

    public function setSaldoCup(?float $saldoCup): self
    {
        $this->saldoCup = $saldoCup;

        return $this;
    }

    public function getSaldoCuc(): ?float
    {
        return $this->saldoCuc;
    }

    public function setSaldoCuc(?float $saldoCuc): self
    {
        $this->saldoCuc = $saldoCuc;

        return $this;
    }

    public function getSolicitud(): ?Solicitud
    {
        return $this->solicitud;
    }

    public function setSolicitud(?Solicitud $solicitud): self
    {
        $this->solicitud = $solicitud;

        return $this;
    }

}
