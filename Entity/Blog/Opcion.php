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

    /**
     * @ORM\Column(name="tipo", type="string", length=3)
     */
    private $tipo;


    public function getToken(): ?string
    {
        return $this->token;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
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

    public function getTipo(): ?string
    {
        return $this->tipo;
    }

    public function setTipo(string $tipo): self
    {
        $this->tipo = $tipo;

        return $this;
    }
}
