<?php

namespace App\Entity\Sistema;

use App\Entity\Traits\IdTrait;
use App\Repository\Sistema\PaisRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="nexus.con_paises")
 * @ORM\Entity(repositoryClass=PaisRepository::class)
 */
class Pais
{
    use IdTrait;

     /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=255, unique=true)
     */
    private $nombre;

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    /*
     *  __toString
     */
    public function __toString ()
    {
        return $this->nombre;
    }
}
