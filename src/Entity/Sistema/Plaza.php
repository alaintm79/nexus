<?php

namespace App\Entity\Sistema;

use App\Repository\Sistema\PlazaRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="nexus.nx_plazas")
 * @ORM\Entity(repositoryClass=PlazaRepository::class)
 * @UniqueEntity(fields={"nombre"}, message="Plaza ya registrada!.")
 */
class Plaza
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
    private $nombre;

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

    /*
     *  __toString
     */

    public function __toString (): ?string
    {
        return $this->nombre;
    }
}
