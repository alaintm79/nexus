<?php

namespace App\Entity\Blog;

use App\Entity\Traits\IdTrait;
use App\Entity\Traits\IsActiveTrait;
use App\Entity\Traits\ObservacionTrait;
use App\Entity\Traits\TimeStampableTrait;
use App\Repository\Blog\ArchivoRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * Archivo
 *
 * @ORM\Table(name="nexus.blog_archivos")
 * @ORM\Entity(repositoryClass=ArchivoRepository::class)
 */
class Archivo
{
    use IdTrait, IsActiveTrait, TimeStampableTrait, ObservacionTrait;

    /**
     * @var string
     *
     * @ORM\Column(name="directorio", type="string", length=255)
     */
    private $directorio;

    /**
     * @var string
     *
     * @ORM\Column(name="ruta", type="string", length=255, unique=true)
     */
    private $ruta;

    public function getDirectorio(): ?string
    {
        return $this->directorio;
    }

    public function setDirectorio(?string $directorio): self
    {
        $this->directorio = $directorio;

        return $this;
    }

    public function getRuta(): ?string
    {
        return $this->ruta;
    }

    public function setRuta(?string $ruta): self
    {
        $this->ruta = $ruta;

        return $this;
    }
}
