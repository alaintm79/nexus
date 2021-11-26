<?php

namespace App\Entity\Blog;

use App\Repository\Blog\EnlaceTipoRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="nexus.blog_enlaces_tipos")
 * @ORM\Entity(repositoryClass=EnlaceTipoRepository::class)
 */
class EnlaceTipo
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $tipo;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTipo(): ?string
    {
        return $this->tipo;
    }

    public function setTipo(string $tipo): self
    {
        $this->tipo = $tipo;

        return $this;
    }

    /*
     *  __toString
     */
    public function __toString ()
    {
        return $this->tipo;
    }
}
