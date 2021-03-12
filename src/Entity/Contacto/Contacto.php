<?php

namespace App\Entity\Contacto;

use App\Entity\Sistema\Pais;
use App\Entity\Traits\IdTrait;
use App\Entity\Traits\ObservacionTrait;
use App\Entity\Traits\TimeStampableTrait;
use App\Repository\Contacto\ContactoRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="nexus.con_contactos")
 * @ORM\Entity(repositoryClass=ContactoRepository::class)
 */
class Contacto
{
    use IdTrait, TimeStampableTrait, ObservacionTrait;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=255)
     * @Assert\NotBlank
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="apellidos", type="string", length=255, nullable=true)
     */
    private $apellidos;

    /**
     * @var string
     *
     * @ORM\Column(name="cargo", type="string", length=255, nullable=true)
     */
    private $cargo;

    /**
     * @var string
     *
     * @ORM\Column(name="telefono_fijo", type="string", length=255, nullable=true)
     * @Assert\Regex(pattern="/^[0-9\/]+$/i", match=true, message="Valor no permitido")
     */
    private $telefonoFijo;

    /**
     * @var string
     *
     * @ORM\Column(name="telefono_fijo_trabajo", type="string", length=255, nullable=true)
     * @Assert\Regex(pattern="/^[0-9\/]+$/i", match=true, message="Valor no permitido")
     */
    private $telefonoFijoTrabajo;

    /**
     * @var string
     *
     * @ORM\Column(name="extension", type="string", length=150, nullable=true)
     * @Assert\Regex(pattern="/^[0-9\/]+$/i", match=true, message="Valor no permitido")
     */
    private $extension;

    /**
     * @var string
     *
     * @ORM\Column(name="telefono_movil", type="string", length=255, nullable=true)
     * @Assert\Regex(pattern="/^[0-9\/]+$/i", match=true, message="Valor no permitido")
     */
    private $telefonoMovil;

    /**
     * @var string
     *
     * @ORM\Column(name="correo1", type="string", length=150, nullable=true)
     */
    private $correo1;

    /**
     * @var string
     *
     * @ORM\Column(name="correo2", type="string", length=150, nullable=true)
     */
    private $correo2;

    /**
     * @var string
     *
     * @ORM\Column(name="direccion", type="string", length=255, nullable=true)
     */
    private $direccion;

    /**
     * @var string
     *
     * @ORM\Column(name="direccion_trabajo", type="string", length=255, nullable=true)
     */
    private $direccionTrabajo;

    /**
     * @var string
     *
     * @ORM\Column(name="ci", type="string", length=11, nullable=true, unique=true)
     */
    private $ci;

    /**
     * @ORM\ManyToOne(targetEntity=Ubicacion::class)
     */
    private $ubicacion;

    /**
     * @ORM\ManyToOne(targetEntity=Pais::class)
     */
    private $pais;

    /**
     * @ORM\ManyToOne(targetEntity=Perfil::class)
     */
    private $perfil;

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getApellidos(): ?string
    {
        return $this->apellidos;
    }

    public function setApellidos(string $apellidos): self
    {
        $this->apellidos = $apellidos;

        return $this;
    }

    public function getCargo(): ?string
    {
        return $this->cargo;
    }

    public function setCargo(?string $cargo): self
    {
        $this->cargo = $cargo;

        return $this;
    }

    public function getTelefonoFijo(): ?string
    {
        return $this->telefonoFijo;
    }

    public function setTelefonoFijo(?string $telefonoFijo): self
    {
        $this->telefonoFijo = $telefonoFijo;

        return $this;
    }

    public function getTelefonoFijoTrabajo(): ?string
    {
        return $this->telefonoFijoTrabajo;
    }

    public function setTelefonoFijoTrabajo(?string $telefonoFijoTrabajo): self
    {
        $this->telefonoFijoTrabajo = $telefonoFijoTrabajo;

        return $this;
    }

    public function getExtension(): ?string
    {
        return $this->extension;
    }

    public function setExtension(?string $extension): self
    {
        $this->extension = $extension;

        return $this;
    }

    public function getTelefonoMovil(): ?string
    {
        return $this->telefonoMovil;
    }

    public function setTelefonoMovil(?string $telefonoMovil): self
    {
        $this->telefonoMovil = $telefonoMovil;

        return $this;
    }

    public function getCorreo1(): ?string
    {
        return $this->correo1;
    }

    public function setCorreo1(?string $correo1): self
    {
        $this->correo1 = $correo1;

        return $this;
    }

    public function getCorreo2(): ?string
    {
        return $this->correo2;
    }

    public function setCorreo2(?string $correo2): self
    {
        $this->correo2 = $correo2;

        return $this;
    }

    public function getDireccion(): ?string
    {
        return $this->direccion;
    }

    public function setDireccion(?string $direccion): self
    {
        $this->direccion = $direccion;

        return $this;
    }

    public function getDireccionTrabajo(): ?string
    {
        return $this->direccionTrabajo;
    }

    public function setDireccionTrabajo(?string $direccionTrabajo): self
    {
        $this->direccionTrabajo = $direccionTrabajo;

        return $this;
    }

    public function getCi(): ?string
    {
        return $this->ci;
    }

    public function setCi(?string $ci): self
    {
        $this->ci = $ci;

        return $this;
    }

    public function getUbicacion(): ?Ubicacion
    {
        return $this->ubicacion;
    }

    public function setUbicacion(?Ubicacion $ubicacion): self
    {
        $this->ubicacion = $ubicacion;

        return $this;
    }

    public function getPais(): ?Pais
    {
        return $this->pais;
    }

    public function setPais(?Pais $pais): self
    {
        $this->pais = $pais;

        return $this;
    }

    public function getPerfil(): ?Perfil
    {
        return $this->perfil;
    }

    public function setPerfil(?Perfil $perfil): self
    {
        $this->perfil = $perfil;

        return $this;
    }

    /**
     * @Assert\IsTrue(message="CI no valido")
     */
    public function isCiValid(): ?bool
    {
        if(null === $this->ci){
            return true;
        }

        $val = $this->ci;
        $anno = $ci[6] <= 5 ? '19'.substr($val, 0, 2) : '20'.substr($val, 0, 2);
        $mes = substr($val, 2, 2);
        $dia = substr($val, 4, 2);

        if($anno < 1940){
            return false;
        }

        return \checkdate($mes, $dia, $anno);
    }
}
