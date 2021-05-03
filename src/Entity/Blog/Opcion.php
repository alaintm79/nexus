<?php

namespace App\Entity\Blog;

use App\Entity\Traits\IdTrait;
use App\Entity\Traits\IsActiveTrait;
use App\Entity\Traits\TimeStampableTrait;
use App\Repository\Blog\OpcionRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * Opcion
 *
 * @ORM\Table(name="nexus.blog_opciones")
 * @ORM\Entity(repositoryClass=OpcionRepository::class)
 */
class Opcion
{
    use IdTrait, IsActiveTrait, TimeStampableTrait;

    /**
     * @var string
     * 
     * @ORM\Column(name="token", type="string", length=255, unique=true)
     */
    private $token;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=255, unique=true)
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="valor", type="text", nullable=true)
     */
    private $valor;


    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(string $token): self
    {
        $this->token = $token;

        return $this;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getValor(): ?string
    {
        return $this->valor;
    }

    public function setValor(?string $valor): self
    {
        $this->valor = $valor;

        return $this;
    }
}
