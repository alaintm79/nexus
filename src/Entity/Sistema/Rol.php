<?php

namespace App\Entity\Sistema;

use App\Entity\Sistema\Grupo;
use App\Entity\Traits\IdTrait;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\Sistema\RolRepository;

/**
 * @ORM\Table(name="nexus.nx_roles")
 * @ORM\Entity(repositoryClass=RolRepository::class)
 */
class Rol
{
    use IdTrait;

    /**
     * @ORM\Column(type="string", length=25)
     */
    private $rol;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $descripcion;

    /**
     * @ORM\ManyToOne(targetEntity=Grupo::class)
     */
    private $grupo;

    public function getRol(): ?string
    {
        return $this->rol;
    }

    public function setRol(string $rol): self
    {
        $this->rol = $rol;

        return $this;
    }

    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function setDescripcion(string $descripcion): self
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    public function getGrupo(): ?Grupo
    {
        return $this->grupo;
    }

    public function setGrupo(?Grupo $grupo): self
    {
        $this->grupo = $grupo;

        return $this;
    }
}
