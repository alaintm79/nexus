<?php

namespace App\Entity\Logistica\Contrato;

use App\Entity\Traits\IdTrait;
use App\Entity\Traits\TimeStampableTrait;
use App\Entity\Logistica\SolicitudPago\SolicitudPago;
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
     * @ORM\OneToOne(targetEntity=SolicitudPago::class)
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

    public function getSolicitud(): ?SolicitudPago
    {
        return $this->solicitud;
    }

    public function setSolicitud(?SolicitudPago $solicitud): self
    {
        $this->solicitud = $solicitud;

        return $this;
    }

}
