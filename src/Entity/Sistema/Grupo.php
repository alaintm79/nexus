<?php

namespace App\Entity\Sistema;

use App\Entity\Traits\IdTrait;
use App\Repository\Sistema\GrupoRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="nexus.nx_grupos")
 * @ORM\Entity(repositoryClass=GrupoRepository::class)
 */
class Grupo
{
    use IdTrait;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $grupo;

    public function getGrupo(): ?string
    {
        return $this->grupo;
    }

    public function setGrupo(string $grupo): self
    {
        $this->grupo = $grupo;

        return $this;
    }

    /*
     *  __toString
     */
    public function __toString ()
    {
        return $this->grupo;
    }
}
