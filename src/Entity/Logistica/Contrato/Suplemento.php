<?php

namespace App\Entity\Logistica\Contrato;

use App\Entity\Logistica\Contrato\Contrato;
use App\Entity\Logistica\Contrato\Estado;
use App\Entity\Logistica\Contrato\Vigencia;
use App\Entity\Traits\IdTrait;
use App\Entity\Traits\IsModificableTrait;
use App\Entity\Traits\ObservacionTrait;
use App\Entity\Traits\RegistroTrait;
use App\Entity\Traits\TimeStampableTrait;
use App\Repository\Logistica\Contrato\SuplementoRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Suplemento
 *
 * @ORM\Table(name="nexus.cto_suplementos")
 * @ORM\Entity(repositoryClass=SuplementoRepository::class)
 */
class Suplemento
{
    use IdTrait, RegistroTrait, ObservacionTrait, TimeStampableTrait, IsModificableTrait;

    /**
     * @ORM\ManyToOne(targetEntity=Contrato::class)
     * @ORM\JoinColumn(name="contrato_id", referencedColumnName="id")
     */
    private $contrato;

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
     * @ORM\Column(name="tipo", type="string", length=1)
     */
    private $tipo;

    /**
     * @ORM\ManyToOne(targetEntity=Estado::class)
     */
    private $estado;

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

    public function setTipo(string $tipo): self
    {
        $this->tipo = $tipo;

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

    public function getEstado(): ?Estado
    {
        return $this->estado;
    }

    public function setEstado(?Estado $estado): self
    {
        $this->estado = $estado;

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
}
