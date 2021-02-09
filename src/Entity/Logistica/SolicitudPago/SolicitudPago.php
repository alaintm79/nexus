<?php

namespace App\Entity\Logistica\SolicitudPago;

use App\Entity\Logistica\Contrato\Contrato;
use App\Entity\Logistica\SolicitudPago\Acapite;
use App\Entity\Logistica\SolicitudPago\Estado;
use App\Entity\Logistica\SolicitudPago\InstrumentoPago;
use App\Entity\Logistica\SolicitudPago\TipoDocumento;
use App\Entity\Logistica\SolicitudPago\TipoPago;
use App\Entity\Sistema\Usuario;
use App\Entity\Traits\IdTrait;
use App\Entity\Traits\ObservacionTrait;
use App\Entity\Traits\TimeStampableTrait;
use App\Repository\Logistica\SolicitudPago\SolicitudPagoRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="nexus.sp_solicitudes_pagos")
 * @ORM\Entity(repositoryClass=SolicitudPagoRepository::class)
 */
class SolicitudPago
{
    use IdTrait, ObservacionTrait, TimeStampableTrait;

    /**
     * @var string
     *
     * @ORM\Column(name="no_documento_primario", type="string", length=255)
     */
    private $noDocumentoPrimario;

    /**
     * @var string
     *
     * @ORM\Column(name="no_documento_secundario", type="string", length=255, nullable=true)
     */
    private $noDocumentoSecundario;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_documento", type="date")
     */
    private $fechaDocumento;

    /**
     * @ORM\ManyToOne(targetEntity=TipoDocumento::class)
     * @ORM\JoinColumn(name="tipo_documento_id", referencedColumnName="id")
     */
    private $tipoDocumento;

    /**
     * @ORM\ManyToOne(targetEntity=Contrato::class)
     */
    private $contrato;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_solicitud", type="date")
     */
    private $fechaSolicitud;

    /**
     * @ORM\ManyToOne(targetEntity=TipoPago::class)
     * @ORM\JoinColumn(name="tipo_pago_id", referencedColumnName="id")
     */
    private $tipoPago;

    /**
     * @ORM\ManyToOne(targetEntity=InstrumentoPago::class)
     * @ORM\JoinColumn(name="instrumento_pago_id", referencedColumnName="id")
     */
    private $instrumentoPago;

    /**
     * @var float
     *
     * @ORM\Column(name="importe_cup", type="decimal", precision=10, scale=2, nullable=true)
     */
    private $importeCup;

    /**
     * @var float
     *
     * @ORM\Column(name="importe_cuc", type="decimal", precision=10, scale=2, nullable=true)
     */
    private $importeCuc;

    /**
     * @var float
     *
     * @ORM\Column(name="importe_total", type="decimal", precision=10, scale=2, nullable=true)
     */
    private $importeTotal;

    /**
     * @var string
     *
     * @ORM\Column(name="objetivo", type="string", length=255)
     */
    private $objetivo;

    /**
     * @ORM\ManyToOne(targetEntity=Acapite::class)
     */
    private $acapite;

    /**
     * @ORM\ManyToOne(targetEntity=Estado::class)
     */
    private $estado;

    /**
     * @ORM\ManyToOne(targetEntity=Usuario::class)
     */
    private $usuario;

    /**
     * @var string
     *
     * @Assert\File(
     *      maxSize = "2048k",
     *      mimeTypes={ "image/jpeg", "image/jpg", "image/png", "application/pdf" },
     *      mimeTypesMessage = "Por favor suba un archivo valido.",
     * )
     * @ORM\Column(name="documento_primario", type="string", length=255, nullable=true)
     */
    private $documentoPrimario;

    /**
     * @var string
     *
     * @Assert\File(
     *      maxSize = "2048k",
     *      mimeTypes={ "image/jpeg", "image/jpg", "image/png", "application/pdf" },
     *      mimeTypesMessage = "Por favor suba un archivo valido.",
     * )
     * @ORM\Column(name="documento_secundario", type="string", length=255, nullable=true)
     */
    private $documentoSecundario;

    public function getNoDocumentoPrimario(): ?string
    {
        return $this->noDocumentoPrimario;
    }

    public function setNoDocumentoPrimario(string $noDocumentoPrimario): self
    {
        $this->noDocumentoPrimario = $noDocumentoPrimario;

        return $this;
    }

    public function getNoDocumentoSecundario(): ?string
    {
        return $this->noDocumentoSecundario;
    }

    public function setNoDocumentoSecundario(?string $noDocumentoSecundario): self
    {
        $this->noDocumentoSecundario = $noDocumentoSecundario;

        return $this;
    }

    public function getFechaDocumento(): ?\DateTimeInterface
    {
        return $this->fechaDocumento;
    }

    public function setFechaDocumento(\DateTimeInterface $fechaDocumento): self
    {
        $this->fechaDocumento = $fechaDocumento;

        return $this;
    }

    public function getFechaSolicitud(): ?\DateTimeInterface
    {
        return $this->fechaSolicitud;
    }

    public function setFechaSolicitud(\DateTimeInterface $fechaSolicitud): self
    {
        $this->fechaSolicitud = $fechaSolicitud;

        return $this;
    }

    public function getImporteCup(): ?string
    {
        return $this->importeCup;
    }

    public function setImporteCup(?string $importeCup): self
    {
        $this->importeCup = $importeCup;

        return $this;
    }

    public function getImporteCuc(): ?string
    {
        return $this->importeCuc;
    }

    public function setImporteCuc(?string $importeCuc): self
    {
        $this->importeCuc = $importeCuc;

        return $this;
    }

    public function getImporteTotal(): ?string
    {
        return $this->importeTotal;
    }

    public function setImporteTotal(?string $importeTotal): self
    {
        $this->importeTotal = $importeTotal;

        return $this;
    }

    public function getObjetivo(): ?string
    {
        return $this->objetivo;
    }

    public function setObjetivo(string $objetivo): self
    {
        $this->objetivo = $objetivo;

        return $this;
    }

    public function getDocumentoPrimario(): ?string
    {
        return $this->documentoPrimario;
    }

    public function setDocumentoPrimario(?string $documentoPrimario): self
    {
        $this->documentoPrimario = $documentoPrimario;

        return $this;
    }

    public function getDocumentoSecundario(): ?string
    {
        return $this->documentoSecundario;
    }

    public function setDocumentoSecundario(?string $documentoSecundario): self
    {
        $this->documentoSecundario = $documentoSecundario;

        return $this;
    }

    public function getTipoDocumento(): ?TipoDocumento
    {
        return $this->tipoDocumento;
    }

    public function setTipoDocumento(?TipoDocumento $tipoDocumento): self
    {
        $this->tipoDocumento = $tipoDocumento;

        return $this;
    }

    public function getContrato(): ?Contrato
    {
        return $this->contrato;
    }

    public function setContrato(?Contrato $contrato): self
    {
        $this->contrato = $contrato;

        return $this;
    }

    public function getTipoPago(): ?TipoPago
    {
        return $this->tipoPago;
    }

    public function setTipoPago(?TipoPago $tipoPago): self
    {
        $this->tipoPago = $tipoPago;

        return $this;
    }

    public function getInstrumentoPago(): ?InstrumentoPago
    {
        return $this->instrumentoPago;
    }

    public function setInstrumentoPago(?InstrumentoPago $instrumentoPago): self
    {
        $this->instrumentoPago = $instrumentoPago;

        return $this;
    }

    public function getAcapite(): ?Acapite
    {
        return $this->acapite;
    }

    public function setAcapite(?Acapite $acapite): self
    {
        $this->acapite = $acapite;

        return $this;
    }

    public function getEstado(): ?Estado
    {
        return $this->estado;
    }

    public function setEstado(?Estado $estado): self
    {
        $this->estado = $estado;

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

    /*
     *  __toString
     */
    public function __toString ()
    {
        return $this->tipo;
    }
}
