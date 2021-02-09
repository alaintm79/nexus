<?php

namespace App\Entity\Logistica;

use App\Entity\Traits\IdTrait;
use App\Entity\Traits\IsModificableTrait;
use App\Entity\Traits\ObservacionTrait;
use App\Entity\Traits\TimeStampableTrait;
use App\Repository\Logistica\ProveedorClienteRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * ProveedorCliente
 *
 * @ORM\Table(name="nexus.cto_proveedores_clientes")
 * @ORM\Entity(repositoryClass=ProveedorClienteRepository::class)
 * @UniqueEntity(fields={"nombre"}, message="Nombre de Proveedor / Cliente en uso!")
 * @UniqueEntity(fields={"codigoReup"}, message="Código REUP existente")
 */
class ProveedorCliente
{
    use IdTrait, IsModificableTrait, ObservacionTrait, TimeStampableTrait;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=255, unique=true)
     * @Assert\NotNull()
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="codigo_reup", type="string", length=255, unique=true, nullable=true)
     */
    private $codigoReup;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_cliente", type="boolean", nullable=true)
     */
    private $isCliente;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_proveedor", type="boolean", nullable=true)
     */
    private $isProveedor = true;

    /**
     * @var integer
     *
     * @ORM\Column(name="cuenta_cup", type="integer", unique=true, nullable=true)
     * @Assert\Regex(
     *     pattern     = "/^[0-9]+$/i",
     *     htmlPattern = "^[0-9]+$",
     *     message="Este valor no es válido."
     * )
     */
    private $cuentaCup;

    /**
     * @var string
     *
     * @ORM\Column(name="titular_cuenta_cup", type="string", length=255, unique=true, nullable=true)
     */
    private $titularCuentaCup;

    /**
     * @var integer
     *
     * @ORM\Column(name="cuenta_cuc", type="integer", unique=true, nullable=true)
     * @Assert\Regex(
     *     pattern     = "/^[0-9]+$/i",
     *     htmlPattern = "^[0-9]+$",
     *     message="Este valor no es válido."
     * )
     */
    private $cuentaCuc;

    /**
     * @var string
     *
     * @ORM\Column(name="titular_cuenta_cuc", type="string", length=255, unique=true, nullable=true)
     */
    private $titularCuentaCuc;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_modificable", type="boolean", nullable=true)
     */
    private $isModificable = true;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getCodigoReup(): ?string
    {
        return $this->codigoReup;
    }

    public function setCodigoReup(?string $codigoReup): self
    {
        $this->codigoReup = $codigoReup;

        return $this;
    }

    public function getIsCliente(): ?bool
    {
        return $this->isCliente;
    }

    public function setIsCliente(?bool $isCliente): self
    {
        $this->isCliente = $isCliente;

        return $this;
    }

    public function getIsProveedor(): ?bool
    {
        return $this->isProveedor;
    }

    public function setIsProveedor(?bool $isProveedor): self
    {
        $this->isProveedor = $isProveedor;

        return $this;
    }

    public function getCuentaCup(): ?int
    {
        return $this->cuentaCup;
    }

    public function setCuentaCup(?int $cuentaCup): self
    {
        $this->cuentaCup = $cuentaCup;

        return $this;
    }

    public function getTitularCuentaCup(): ?string
    {
        return $this->titularCuentaCup;
    }

    public function setTitularCuentaCup(?string $titularCuentaCup): self
    {
        $this->titularCuentaCup = $titularCuentaCup;

        return $this;
    }

    public function getCuentaCuc(): ?int
    {
        return $this->cuentaCuc;
    }

    public function setCuentaCuc(?int $cuentaCuc): self
    {
        $this->cuentaCuc = $cuentaCuc;

        return $this;
    }

    public function getTitularCuentaCuc(): ?string
    {
        return $this->titularCuentaCuc;
    }

    public function setTitularCuentaCuc(?string $titularCuentaCuc): self
    {
        $this->titularCuentaCuc = $titularCuentaCuc;

        return $this;
    }

    public function getIsModificable(): ?bool
    {
        return $this->isModificable;
    }

    public function setIsModificable(?bool $isModificable): self
    {
        $this->isModificable = $isModificable;

        return $this;
    }

    /*
     *  __toString
     */

    public function __toString ()
    {
        return \strtoupper($this->nombre);
    }
}
