<?php

namespace App\Entity\Contacto;

use App\Entity\Traits\IdTrait;
use App\Entity\Traits\TimeStampableTrait;
use App\Repository\Contacto\PerfilRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="nexus.con_perfiles")
 * @ORM\Entity(repositoryClass=PerfilRepository::class)
 */
class Perfil
{
    use IdTrait, TimeStampableTrait;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=255)
     * @Assert\NotBlank
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
