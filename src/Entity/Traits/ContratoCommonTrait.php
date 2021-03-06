<?php

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Logistica\Contrato\Vigencia;
use Symfony\Component\Validator\Constraints as Assert;

trait ContratoCommonTrait {

    /**
     * @ORM\ManyToOne(targetEntity=Vigencia::class)
     */
    private $vigencia;

    /**
     * @var float
     *
     * @ORM\Column(name="valor_cup", type="decimal", precision=10, scale=2, nullable=true)
     * @Assert\Type(type="numeric", groups={"new","edit"})
     */
    private $valorCup;

    /**
     * @var float
     *
     * @ORM\Column(name="valor_cuc", type="decimal", precision=10, scale=2,  nullable=true)
     * @Assert\Type(type="numeric", groups={"new","edit"})
     */
    private $valorCuc;

    /**
     * @var int
     *
     * @ORM\Column(name="registro_comite", type="integer", nullable=true)
     * @Assert\Regex(pattern="/^[0-9]+$/i", match=true, message="Valor no permitido", groups={"approve"})
     * @Assert\GreaterThanOrEqual(value=0, groups={"approve"})
     * @Assert\LessThanOrEqual(value="999", groups={"approve"})
     */
    private $registroComite;

    /**
     * @var int
     *
     * @ORM\Column(name="registro_acuerdo", type="integer", nullable=true)
     * @Assert\Regex(pattern="/^[0-9]+$/i", match=true, message="Valor no permitido", groups={"approve"})
     * @Assert\GreaterThanOrEqual(value=0, groups={"approve"})
     * @Assert\LessThanOrEqual(value="999", groups={"approve"})
     */
    private $registroAcuerdo;

    /**
     * @var int
     *
     * @ORM\Column(name="cancelado_consejo", type="integer", nullable=true)
     * @Assert\Regex(pattern="/^[0-9]+$/i", match=true, message="Valor no permitido", groups={"edit"})
     * @Assert\GreaterThanOrEqual(value=0, groups={"cancel"})
     * @Assert\LessThanOrEqual(value="999", groups={"cancel"})
     */
    private $canceladoComite;

    /**
     * @var int
     *
     * @ORM\Column(name="cancelado_acuerdo", type="integer", nullable=true)
     * @Assert\Regex(pattern="/^[0-9]+$/i", match=true, message="Valor no permitido", groups={"edit"})
     * @Assert\GreaterThanOrEqual(value=0, groups={"cancel"})
     * @Assert\LessThanOrEqual(value="999", groups={"cancel"})
     */
    private $canceladoAcuerdo;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_aprobado", type="date", nullable=true)
     * @Assert\Date(groups={"approve"})
     * @Assert\LessThanOrEqual(value="today", message="Fecha fuera de rango", groups={"approve"})
     */
    private $fechaAprobado;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_cancelado", type="date", nullable=true)
     * @Assert\Date(groups={"cancel"})
     * @Assert\LessThanOrEqual(value="today", message="Fecha fuera de rango", groups={"cancel"})
     * @Assert\GreaterThan(propertyPath="fechaAprobado",
     *     message="Fecha fuera de rango",
     *     groups={"cancel"}
     * )
     */
    private $fechaCancelado;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_firma", type="date", nullable=true)
     * @Assert\Date(groups={"firm"})
     * @Assert\LessThanOrEqual(value="today", message="Fecha fuera de rango", groups={"firm"})
     * @Assert\GreaterThanOrEqual(propertyPath="fechaAprobado",
     *     message="Fecha fuera de rango",
     *     groups={"firm"}
     * )
     */
    private $fechaFirma;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_vigencia", type="date", nullable=true)
     * @Assert\Date(groups={"new", "edit"})
     * @Assert\GreaterThanOrEqual(value="today", message="Fecha fuera de rango", groups={"new", "edit"})
     */
    private $fechaVigencia;

    public function getVigencia(): ?Vigencia
    {
        return $this->vigencia;
    }

    public function setVigencia(?Vigencia $vigencia): self
    {
        $this->vigencia = $vigencia;

        return $this;
    }

    public function getValorCup(): ?string
    {
        return $this->valorCup;
    }

    public function setValorCup(?string $valorCup): self
    {
        $this->valorCup = $valorCup;

        return $this;
    }

    public function getValorCuc(): ?string
    {
        return $this->valorCuc;
    }

    public function setValorCuc(?string $valorCuc): self
    {
        $this->valorCuc = $valorCuc;

        return $this;
    }

    public function getRegistroComite(): ?int
    {
        return $this->registroComite;
    }

    public function setRegistroComite(?int $registroComite): self
    {
        $this->registroComite = $registroComite;

        return $this;
    }

    public function getRegistroAcuerdo(): ?int
    {
        return $this->registroAcuerdo;
    }

    public function setRegistroAcuerdo(?int $registroAcuerdo): self
    {
        $this->registroAcuerdo = $registroAcuerdo;

        return $this;
    }

    public function getCanceladoComite(): ?int
    {
        return $this->canceladoComite;
    }

    public function setCanceladoComite(?int $canceladoComite): self
    {
        $this->canceladoComite = $canceladoComite;

        return $this;
    }

    public function getCanceladoAcuerdo(): ?int
    {
        return $this->canceladoAcuerdo;
    }

    public function setCanceladoAcuerdo(?int $canceladoAcuerdo): self
    {
        $this->canceladoAcuerdo = $canceladoAcuerdo;

        return $this;
    }

    public function getFechaAprobado(): ?\DateTimeInterface
    {
        return $this->fechaAprobado;
    }

    public function setFechaAprobado(?\DateTimeInterface $fechaAprobado): self
    {
        $this->fechaAprobado = $fechaAprobado;

        return $this;
    }

    public function getFechaCancelado(): ?\DateTimeInterface
    {
        return $this->fechaCancelado;
    }

    public function setFechaCancelado(?\DateTimeInterface $fechaCancelado): self
    {
        $this->fechaCancelado = $fechaCancelado;

        return $this;
    }

    public function getFechaFirma(): ?\DateTimeInterface
    {
        return $this->fechaFirma;
    }

    public function setFechaFirma(?\DateTimeInterface $fechaFirma): self
    {
        $this->fechaFirma = $fechaFirma;

        return $this;
    }

    public function getFechaVigencia(): ?\DateTimeInterface
    {
        return $this->fechaVigencia;
    }

    public function setFechaVigencia(?\DateTimeInterface $fechaVigencia): self
    {
        $this->fechaVigencia = $fechaVigencia;

        return $this;
    }
}
