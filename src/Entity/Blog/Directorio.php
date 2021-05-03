<?php

namespace App\Entity\Blog;

use App\Entity\Traits\IdTrait;
use App\Entity\Traits\IsActiveTrait;
use App\Entity\Traits\ObservacionTrait;
use App\Entity\Traits\TimeStampableTrait;
use App\Repository\Blog\DirectorioRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Directorio
 *
 * @ORM\Table(name="nexus.blog_directorios")
 * @ORM\Entity(repositoryClass=DirectorioRepository::class)
 */
class Directorio
{
    use IdTrait, IsActiveTrait, TimeStampableTrait, ObservacionTrait;

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
     * @ORM\Column(name="ruta", type="string", length=255, unique=true)
     * @Assert\Regex(pattern="/^[a-zA-Z0-9\/]+$/i", match=true, message="Valor no permitido")
     */
    private $ruta;

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(?string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getRuta(): ?string
    {
        return $this->ruta;
    }

    public function setRuta(?string $ruta): self
    {
        $this->ruta = \strtolower($ruta);

        return $this;
    }
}
