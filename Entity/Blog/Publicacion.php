<?php

namespace App\Entity\Blog;

use App\Entity\Blog\Categoria;
use App\Entity\Blog\Estado;
use App\Entity\Sistema\Usuario;
use App\Entity\Traits\IdTrait;
use App\Entity\Traits\TimeStampableTrait;
use App\Repository\Blog\PublicacionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
     * @ORM\Column(name="fecha_publicacion", type="datetime")
     */
    private $fechaPublicacion;

    /**
     * @ORM\Column(name="is_sticky", type="boolean", nullable=true)
     */
    private $isSticky;

    /**
     * @ORM\Column(name="thumbnail", type="string", length=255, nullable=true)
     */
    private $thumbnail;

    /**
     * @ORM\OneToMany(targetEntity=Comentario::class, mappedBy="publicacion", cascade={"remove"}, orphanRemoval=true)
     */
    private $comentarios;

    /**
     * @ORM\Column(name="is_active_comentarios", type="boolean", nullable=true)
     */
    private $isActiveComentarios;

    /**
     * @ORM\Column(name="is_delete", type="boolean", nullable=true)
     */
    private $isDelete = \false;

    /**
     * @ORM\Column(name="is_sent", type="boolean", nullable=true)
     */
    private $isSent = \false;

    /**
     * @ORM\Column(name="tomado_de_titulo", type="string", length=255, nullable=true)
     */
    private $tomadoDeTitulo;

    /**
     * @ORM\Column(name="tomado_de_url", type="string", length=255, nullable=true)
     * @Assert\Url(
     *      protocols = {"http", "https"}
     * )
     */
    private $tomadoDeUrl;

    /**
     * @ORM\OneToMany(targetEntity=PublicacionCounter::class, mappedBy="publicacion", cascade={"remove"}, orphanRemoval=true)
     */
    private $counter;

    public function  __construct()
    {
        $this->fechaPublicacion = new \DateTime('now');
        $this->comentarios = new ArrayCollection();
        $this->counter = new ArrayCollection();
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

    public function getThumbnail(): ?string
    {
        return $this->thumbnail;
    }

    public function setThumbnail(?string $thumbnail): self
    {
        $this->thumbnail = $thumbnail;

        return $this;
    }

    /**
     * @return Collection|Comentario[]
     */
    public function getComentarios(): Collection
    {
        return $this->comentarios;
    }

    public function addComentario(Comentario $comentario): self
    {
        if (!$this->comentarios->contains($comentario)) {
            $this->comentarios[] = $comentario;
            $comentario->setPublicacion($this);
        }

        return $this;
    }

    public function removeComentario(Comentario $comentario): self
    {
        if ($this->comentarios->removeElement($comentario)) {
            // set the owning side to null (unless already changed)
            if ($comentario->getPublicacion() === $this) {
                $comentario->setPublicacion(null);
            }
        }

        return $this;
    }

    public function getIsActiveComentarios(): ?bool
    {
        return $this->isActiveComentarios;
    }

    public function setIsActiveComentarios(bool $isActiveComentarios): self
    {
        $this->isActiveComentarios = $isActiveComentarios;

        return $this;
    }

    public function getIsDelete(): ?bool
    {
        return $this->isDelete;
    }

    public function setIsDelete(?bool $isDelete): self
    {
        $this->isDelete = $isDelete;

        return $this;
    }

    public function getIsSent(): ?bool
    {
        return $this->isSent;
    }

    public function setIsSent(?bool $isSent): self
    {
        $this->isSent = $isSent;

        return $this;
    }

    public function getTomadoDeTitulo(): ?string
    {
        return $this->tomadoDeTitulo;
    }

    public function setTomadoDeTitulo(?string $tomadoDeTitulo): self
    {
        $this->tomadoDeTitulo = $tomadoDeTitulo;

        return $this;
    }

    public function getTomadoDeUrl(): ?string
    {
        return $this->tomadoDeUrl;
    }

    public function setTomadoDeUrl(?string $tomadoDeUrl): self
    {
        $this->tomadoDeUrl = $tomadoDeUrl;

        return $this;
    }

    /**
     * @return Collection|PublicacionCounter[]
     */
    public function getCounter(): Collection
    {
        return $this->counter;
    }

    public function addCounter(PublicacionCounter $counter): self
    {
        if (!$this->counter->contains($counter)) {
            $this->counter[] = $counter;
            $counter->setPublicacion($this);
        }

        return $this;
    }

    public function removeCounter(PublicacionCounter $counter): self
    {
        if ($this->counter->removeElement($counter)) {
            // set the owning side to null (unless already changed)
            if ($counter->getPublicacion() === $this) {
                $counter->setPublicacion(null);
            }
        }

        return $this;
    }
}

