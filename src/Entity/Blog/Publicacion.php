<?php

namespace App\Entity\Blog;

use App\Entity\Blog\Categoria;
use App\Entity\Blog\Estado;
use App\Entity\Sistema\Usuario;
use App\Entity\Traits\IdTrait;
use App\Entity\Traits\TimeStampableTrait;
use App\Repository\Blog\PublicacionRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Post
 *
 * @ORM\Table(name="nexus.blog_publicacion")
 * @ORM\Entity(repositoryClass=PublicacionRepository::class)
 */
class Publicacion
{
    use IdTrait, TimeStampableTrait;

    /**
     * @var string
     *
     * @ORM\Column(name="titulo", type="string", length=255)
     * @Assert\NotBlank()
     */
    private $titulo;

    /**
     * @ORM\ManyToOne(targetEntity=Categoria::class)
     */
    private $categoria;

    /**
     * @var string
     *
     * @ORM\Column(name="resumen", type="text")
     * @Assert\NotBlank()
     */
    private $resumen;

    /**
     * @var string
     *
     * @ORM\Column(name="contenido", type="text", nullable=true)
     * @Assert\NotBlank()
     */
    private $contenido;

    /**
     * @ORM\ManyToOne(targetEntity=Estado::class)
     */
    private $estado;

    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=255, unique=true)
     * @Gedmo\Translatable
     * @Gedmo\Slug(fields={"titulo"})
     */
    private $slug;

    /**
     * @ORM\ManyToOne(targetEntity=Usuario::class)
     */
    private $autor;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_publicacion", type="date")
     */
    private $fechaPublicacion;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isSticky;

    public function  __construct()
    {
        $this->fechaPublicacion = new \DateTime('now');
    }

    public function getTitulo(): ?string
    {
        return $this->titulo;
    }

    public function setTitulo(string $titulo): self
    {
        $this->titulo = $titulo;

        return $this;
    }

    public function getCategoria(): ?Categoria
    {
        return $this->categoria;
    }

    public function setCategoria(?Categoria $categoria): self
    {
        $this->categoria = $categoria;

        return $this;
    }

    public function getResumen(): ?string
    {
        return $this->resumen;
    }

    public function setResumen(string $resumen): self
    {
        $this->resumen = $resumen;

        return $this;
    }

    public function getContenido(): ?string
    {
        return $this->contenido;
    }

    public function setContenido(string $contenido): self
    {
        $this->contenido = $contenido;

        return $this;
    }

    public function getEstado(): ?Estado
    {
        return $this->estado;
    }

    public function setEstado(?Estado $estado): self
    {
        $this->estado = $estado;

        return $this;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function getAutor(): Usuario
    {
        return $this->autor;
    }

    public function setAutor(Usuario $autor): self
    {
        $this->autor = $autor;

        return $this;
    }

    public function getFechaPublicacion(): ?\DateTimeInterface
    {
        return $this->fechaPublicacion;
    }

    public function setFechaPublicacion(?\DateTimeInterface $fechaPublicacion): self
    {
        $this->fechaPublicacion = $fechaPublicacion;

        return $this;
    }

    public function getIsSticky(): ?bool
    {
        return $this->isSticky;
    }

    public function setIsSticky(?bool $isSticky): self
    {
        $this->isSticky = $isSticky;

        return $this;
    }
}

