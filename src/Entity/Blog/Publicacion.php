<?php

namespace App\Entity\Blog;

use App\Entity\Blog\Categoria;
use App\Entity\Blog\Estado;
use App\Entity\Sistema\Usuario;
use App\Entity\Traits\IdTrait;
use App\Entity\Traits\TimeStampableTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Post
 *
 * @ORM\Table(name="nexus.blog_publicacion")
 * @ORM\Entity(repositoryClass=PublicacionRepository::class)
 * @UniqueEntity(fields={"slug"}, message="Enlace en uso en otra publicación!.")
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
     * @ORM\Column(name="contenido", type="text")
     * @Assert\NotBlank()
     */
    private $contenido;

    /**
     * @var string
     *
     * @ORM\Column(name="miniatura", type="string", length=255, nullable=true)
     */
    private $miniatura;

    /**
     * @ORM\ManyToOne(targetEntity=Estado::class)
     */
    private $estado;

    /**
     * @var string
     *
     * @ORM\Column(name="adjunto", type="string", nullable=true)
     */
    private $adjunto;

    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=255, unique=true)
     * @Assert\NotBlank()
     */
    private $slug;

    /**
     * @ORM\ManyToOne(targetEntity=Usuario::class)
     */
    private $autor;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_publicacion", type="datetime")
     */
    private $fechaPublicacion;

    public function __construct(){
        $this->fechaPublicacion = new \DateTime();
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

    public function getMiniatura(): ?string
    {
        return $this->miniatura;
    }

    public function setMiniatura(string $miniatura): ?self
    {
        $this->miniatura = $miniatura;

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

    public function getAdjunto(): ?string
    {
        return $this->adjunto;
    }

    public function setAdjunto(string $adjunto): self
    {
        $this->adjunto = $adjunto;

        return $this;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
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

    public function getFechaPublicacion(): \DateTimeInterface
    {
        return $this->fechaPublicacion;
    }

    public function setFechaPublicacion(\DateTimeInterface $fechaPublicacion): self
    {
        $this->fechaPublicacion = $fechaPublicacion;

        return $this;
    }
}

