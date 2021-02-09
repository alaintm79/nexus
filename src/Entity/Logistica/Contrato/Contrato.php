<?php

namespace App\Entity\Logistica\Contrato;

use App\Entity\Sistema\Unidad;
use App\Entity\Traits\IdTrait;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Traits\ObservacionTrait;
use App\Entity\Logistica\Contrato\Estado;
use App\Entity\Traits\IsModificableTrait;
use App\Entity\Traits\TimeStampableTrait;
use App\Entity\Logistica\ProveedorCliente;
use App\Entity\Logistica\Contrato\Vigencia;
use App\Entity\Logistica\Contrato\Categoria;
use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\Logistica\Contrato\ContratoRepository;

/**
 * Contrato
 *
 * @ORM\Table(name="nexus.cto_contratos")
 * @ORM\Entity(repositoryClass=ContratoRepository::class)
 */
class Contrato
{
    use IdTrait, ObservacionTrait, IsModificableTrait, TimeStampableTrait;

    /**
     * @var string
     *
     * @ORM\Column(name="numero", type="string", length=10, unique=true, nullable=true)
     */
    private $numero;

    /**
     * @var string
     *
     * @ORM\Column(name="objeto", type="text")
     * @Assert\NotBlank(groups={"new","edit"})
     */
    private $objeto;

    /**
     * @var string
     *
     * @ORM\Column(name="tipo", type="string", length=1, nullable=true)
     */
    private $tipo;

    /**
     * @ORM\ManyToOne(targetEntity=Categoria::class)
     */
    private $categoria;

    /**
     * @ORM\ManyToOne(targetEntity=Estado::class)
     */
    private $estado;

    /**
     * @ORM\ManyToOne(targetEntity=Unidad::class)
     */
    private $procedencia;

    /**
     * @ORM\ManyToOne(targetEntity=ProveedorCliente::class)
     * @ORM\JoinColumn(name="proveedor_cliente_id", referencedColumnName="id")
     */
    private $proveedorCliente;

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
     * @ORM\ManyToOne(targetEntity=Vigencia::class)
     */
    private $vigencia;

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
     * @Assert\GreaterThan(propertyPath="fechaAprobado",
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

    /**
     * @var float
     *
     * @ORM\Column(name="valor_ejecucion_cup", type="decimal", precision=10, scale=2, nullable=true)
     */
    private $valorEjecucionCup;

    /**
     * @var float
     *
     * @ORM\Column(name="valor_ejecucion_cuc", type="decimal", precision=10, scale=2, nullable=true)
     */
    private $valorEjecucionCuc;

    /**
     * @var float
     *
     * @ORM\Column(name="valor_total_cup", type="decimal", precision=10, scale=2, nullable=true)
     */
    private $valorTotalCup;

    /**
     * @var float
     *
     * @ORM\Column(name="valor_total_cuc", type="decimal", precision=10, scale=2, nullable=true)
     */
    private $valorTotalCuc;

    public function getNumero(): ?string
    {
        return $this->numero;
    }

    public function setNumero(?Contrato $contrato, ?string $tipo): string
    {
        $anno = new \DateTime();

        if(null === $contrato){
            $contrato = $tipo.'-0-'.$anno->format('Y');
        } else {
            $contrato = $contrato->getNumero();
        }

        $ultimo = explode('-', $contrato);
        $numero = $ultimo[2] === $anno->format('Y') ? $ultimo[1] : 0;
        $actual = str_pad($numero + 1, 3, '0', STR_PAD_LEFT);

        return $this->numero = $tipo.'-'.$actual.'-'.$anno->format('Y');
    }

    public function getObjeto(): ?string
    {
        return $this->objeto;
    }

    public function setObjeto(string $objeto): self
    {
        $this->objeto = $objeto;

        return $this;
    }

    public function getTipo(): ?string
    {
        return $this->tipo;
    }

    public function setTipo(?string $tipo): self
    {
        $this->tipo = $tipo === 'proveedor' ? 'p' : 'c';

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

    public function getValorEjecucionCup(): ?string
    {
        return $this->valorEjecucionCup;
    }

    public function setValorEjecucionCup(?string $valorEjecucionCup): self
    {
        $this->valorEjecucionCup = $valorEjecucionCup;

        return $this;
    }

    public function getValorEjecucionCuc(): ?string
    {
        return $this->valorEjecucionCuc;
    }

    public function setValorEjecucionCuc(?string $valorEjecucionCuc): self
    {
        $this->valorEjecucionCuc = $valorEjecucionCuc;

        return $this;
    }

    public function getValorTotalCup(): ?string
    {
        return $this->valorTotalCup;
    }

    public function setValorTotalCup(?string $valorTotalCup): self
    {
        $this->valorTotalCup = $valorTotalCup;

        return $this;
    }

    public function getValorTotalCuc(): ?string
    {
        return $this->valorTotalCuc;
    }

    public function setValorTotalCuc(?string $valorTotalCuc): self
    {
        $this->valorTotalCuc = $valorTotalCuc;

        return $this;
    }

    public function getCategoria(): ?Categoria
    {
        return $this->categoria;
    }

    public function setCategoria(?Categoria $categoria): self
    {
        $this->categoria = $categoria;

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

    public function getProcedencia(): ?Unidad
    {
        return $this->procedencia;
    }

    public function setProcedencia(?Unidad $procedencia): self
    {
        $this->procedencia = $procedencia;

        return $this;
    }

    public function getProveedorCliente(): ?ProveedorCliente
    {
        return $this->proveedorCliente;
    }

    public function setProveedorCliente(?ProveedorCliente $proveedorCliente): self
    {
        $this->proveedorCliente = $proveedorCliente;

        return $this;
    }

    public function getVigencia(): ?Vigencia
    {
        return $this->vigencia;
    }

    public function setVigencia(?Vigencia $vigencia): self
    {
        $this->vigencia = $vigencia;

        return $this;
    }

    public function __toString()
    {
        return (string) $this->numero;
    }

    /**
     * @Assert\IsTrue(
     *      message = "Fecha.",
     *      groups={"edit"}
     * )
     */
    // public function isFechaAprobadoValid()
    // {
    //     $estado = $this->getEstado()->getEstado();

    //     if($estado === 'CANCELADO' && $this->getTipo() === 'p'){
    //         if(is_null($this->getCanceladoAcuerdo())){
    //             return false;
    //         }
    //     }
    // }

    /**
     * @Assert\IsTrue(
     *      message = "Falta el número de acuerdo.",
     *      groups={"edit"}
     * )
     */
    // public function isCanceladoAcuerdoValid()
    // {
    //     $estado = $this->getEstado()->getEstado();

    //     if($estado === 'CANCELADO' && $this->getTipo() === 'p'){
    //         if(is_null($this->getCanceladoAcuerdo())){
    //             return false;
    //         }
    //     }
    // }

    /**
     * @Assert\IsTrue(
     *      message = "Fecha fuera de rango.",
     *      groups={"edit"}
     * )
     */
    // public function isFechaCanceladoValid()
    // {
    //     if($this->getEstado()->getEstado() === 'CANCELADO'){
    //         if($this->getFechaAprobado() > $this->getFechaCancelado()){
    //             return false;
    //         }
    //     }
    // }
}
