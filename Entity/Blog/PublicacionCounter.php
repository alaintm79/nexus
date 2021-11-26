<?php

namespace App\Entity\Blog;

use App\Entity\Traits\IdTrait;
use App\Repository\Blog\PublicacionCounterRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Table(name="nexus.blog_publicacion_counter")
 * @ORM\Entity(repositoryClass=PublicacionCounterRepository::class)
 */
class PublicacionCounter
{
    use IdTrait;

    /**
     * @ORM\ManyToOne(targetEntity=Publicacion::class, inversedBy="counter")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $publicacion;

    /**
     * @var DateTime $fechaVisita
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="fecha_visita", type="datetime")
     */
    private $fechaVisita;

    /**
     * @var string $ip
     *
     * @ORM\Column(name="ip", type="string", length=45)
     */
    private $ip;

    public function getPublicacion(): ?Publicacion
    {
        return $this->publicacion;
    }

    public function setPublicacion(?Publicacion $publicacion): self
    {
        $this->publicacion = $publicacion;

        return $this;
    }

    public function getFechaVisita(): ?\DateTimeInterface
    {
        return $this->fechaVisita;
    }

    public function setFechaVisita(\DateTimeInterface $fechaVisita): self
    {
        $this->fechaVisita = $fechaVisita;

        return $this;
    }

    public function getIp(): ?string
    {
        return $this->ip;
    }


    public function setIp(string $ip): self
    {
        $this->ip = $ip;

        return $this;
    }
}
