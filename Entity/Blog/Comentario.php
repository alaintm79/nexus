<?php

namespace App\Entity\Blog;

use App\Entity\Sistema\Usuario;
use App\Entity\Traits\IdTrait;
use App\Entity\Traits\TimeStampableTrait;
use App\Repository\Blog\ComentarioRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="nexus.blog_comentarios")
 * @ORM\Entity(repositoryClass=ComentarioRepository::class)
 */
class Comentario
{
    use IdTrait, TimeStampableTrait;

    /**
     * @ORM\ManyToOne(targetEntity=Publicacion::class, inversedBy="comentarios")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $publicacion;

    /**
     * @ORM\ManyToOne(targetEntity=Usuario::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $usuario;

    /**
     * @ORM\Column(name="is_delete", type="boolean")
     */
    private $isDelete= \false;

    /**
     * @ORM\Column(name="is_review", type="boolean")
     */
    private $isReview = \false;

    /**
     * @ORM\Column(name="comentario", type="text")
     * @Assert\Length(
     *      min = 5,
     *      minMessage = "El comentario es demasiado corto {{ limit }} caracteres como mínimo",
     *      allowEmptyString = false,
     * )
     */
    private $comentario;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPublicacion(): ?Publicacion
    {
        return $this->publicacion;
    }

    public function setPublicacion(?Publicacion $publicacion): self
    {
        $this->publicacion = $publicacion;

        return $this;
    }

    public function getUsuario(): ?Usuario
    {
        return $this->usuario;
    }

    public function setUsuario(?Usuario $usuario): self
    {
        $this->usuario = $usuario;

        return $this;
    }

    public function getIsDelete(): ?bool
    {
        return $this->isDelete;
    }

    public function setIsDelete(bool $isDelete): self
    {
        $this->isDelete = $isDelete;

        return $this;
    }

    public function getIsReview(): ?bool
    {
        return $this->isReview;
    }

    public function setIsReview(bool $isReview): self
    {
        $this->isReview = $isReview;

        return $this;
    }

    public function getComentario(): ?string
    {
        return $this->comentario;
    }

    public function setComentario(string $comentario): self
    {
        $this->comentario = $comentario;

        return $this;
    }
}
