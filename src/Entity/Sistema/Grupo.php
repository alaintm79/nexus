<?php

namespace App\Entity\Sistema;

use App\Repository\Sistema\GrupoRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="nexus.nx_grupos")
 * @ORM\Entity(repositoryClass=GrupoRepository::class)
 */
class Grupo
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $grupo;

    public function getId(): ?int
    {
        return $this->id;
    }

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
