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
use App\Entity\Traits\RegistroTrait;
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
    use IdTrait, RegistroTrait, ObservacionTrait, IsModificableTrait, TimeStampableTrait;

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

    public function __toString()
    {
        return (string) $this->numero;
    }
}
