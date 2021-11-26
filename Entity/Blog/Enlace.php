<?php

namespace App\Entity\Blog;

use App\Entity\Traits\IdTrait;
use App\Entity\Traits\IsActiveTrait;
use App\Entity\Traits\TimeStampableTrait;
use App\Repository\Blog\EnlaceRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Enlaces
 *
 * @ORM\Table(name="nexus.blog_enlaces")
 * @ORM\Entity(repositoryClass=EnlaceRepository::class)
 */
class Enlace
{
    use IdTrait, IsActiveTrait, TimeStampableTrait;

    /**
     * @var string
     *
     * @ORM\Column(name="titulo", type="string", length=255)
     */
    private $titulo;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=255)
     * @Assert\Url()
     */
    private $url;

    /**
     * @ORM\ManyToOne(targetEntity=EnlaceTipo::class)
     */
    private $tipo;

    /**
     * @ORM\Column(name="is_menu", type="boolean", nullable=true)
     */
    private $isMenu;

    public function getTitulo(): ?string
    {
        return $this->titulo;
    }

    public function setTitulo(string $titulo): self
    {
        $this->titulo = $titulo;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getTipo(): ?EnlaceTipo
    {
        return $this->tipo;
    }

    public function setTipo(EnlaceTipo $tipo): self
    {
        $this->tipo = $tipo;

        return $this;
    }

    public function getIsMenu(): ?bool
    {
        return $this->isMenu;
    }

    public function setIsMenu(?bool $isMenu): self
    {
        $this->isMenu = $isMenu;

        return $this;
    }
}
