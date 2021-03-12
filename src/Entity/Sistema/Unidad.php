<?php

namespace App\Entity\Sistema;

use App\Entity\Traits\IdTrait;
use App\Repository\Sistema\UnidadRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="nexus.nx_unidades")
 * @ORM\Entity(repositoryClass=UnidadRepository::class)
 */
class Unidad
{
    use IdTrait;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="dominio", type="string", length=150)
     */
    private $dominio;

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getDominio(): ?string
    {
        return $this->dominio;
    }

    public function setDominio(string $dominio): self
    {
        $this->dominio = $dominio;

        return $this;
    }

    /*
     *  __toString
     */

    public function __toString (): ?string
    {
        return $this->nombre;
    }
}
