<?php

namespace App\Entity\Blog;

use App\Entity\Traits\IdTrait;
use App\Repository\Blog\EstadoRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * PublicacionEstado
 *
 * @ORM\Table(name="nexus.blog_estados")
 * @ORM\Entity(repositoryClass=EstadoRepository::class)
 */
class Estado
{
    use IdTrait;

    /**
     * @var string
     *
     * @ORM\Column(name="estado", type="string", length=255, unique=true)
     */
    private $estado;

    /**
     * Get estado
     *
     * @return string
     */
    public function getEstado(): ?string
    {
        return $this->estado;
    }

    public function setEstado(string $estado): self
    {
        $this->estado = $estado;

        return $this;
    }

    /*
     *  __toString
     */
    public function __toString ()
    {
        return \ucfirst($this->estado);
    }
}

