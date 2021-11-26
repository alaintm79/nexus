<?php

namespace App\Entity\Tic\Linea;

use App\Entity\Sistema\Usuario;
use App\Entity\Traits\IdTrait;
use App\Entity\Traits\TimeStampableTrait;
use App\Repository\Tic\Linea\LogUsuarioRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="nexus.tic_lineas_log_usuarios")
 * @ORM\Entity(repositoryClass=LogUsuarioRepository::class)
 */
class LogUsuario
{
    use IdTrait, TimeStampableTrait;

    /**
     * @ORM\ManyToOne(targetEntity=Usuario::class)
     * @ORM\JoinColumn(nullable=true)
     */
    private $usuario;

    /**
     * @ORM\ManyToOne(targetEntity=Linea::class, inversedBy="logUsuario")
     */
    private $linea;

    public function getUsuario(): ?Usuario
    {
        return $this->usuario;
    }

    public function setUsuario(?Usuario $usuario): self
    {
        $this->usuario = $usuario;

        return $this;
    }

    public function getLinea(): ?Linea
    {
        return $this->linea;
    }

    public function setLinea(?Linea $linea): self
    {
        $this->linea = $linea;

        return $this;
    }
}
