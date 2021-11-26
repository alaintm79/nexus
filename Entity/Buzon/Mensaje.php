<?php

namespace App\Entity\Buzon;

use App\Entity\Sistema\Usuario;
use App\Entity\Traits\IdTrait;
use App\Entity\Traits\TimeStampableTrait;
use App\Repository\Buzon\MensajeRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="nexus.buzon_mensaje")
 * @ORM\Entity(repositoryClass=MensajeRepository::class)
 */
class Mensaje
{
    use IdTrait, TimeStampableTrait;

    /**
     * @ORM\ManyToOne(targetEntity=Usuario::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $usuario;

    /**
     * @ORM\ManyToOne(targetEntity=TipoMensaje::class, inversedBy="mensaje")
     * @ORM\JoinColumn(nullable=false)
     */
    private $tipoMensaje;

    /**
     * @ORM\Column(type="text")
     */
    private $mensaje;

    public function getUsuario(): ?Usuario
    {
        return $this->usuario;
    }

    public function setUsuario(?Usuario $usuario): self
    {
        $this->usuario = $usuario;

        return $this;
    }

    public function getTipoMensaje(): ?TipoMensaje
    {
        return $this->tipoMensaje;
    }

    public function setTipoMensaje(?TipoMensaje $tipoMensaje): self
    {
        $this->tipoMensaje = $tipoMensaje;

        return $this;
    }

    public function getMensaje(): ?string
    {
        return $this->mensaje;
    }

    public function setMensaje(string $mensaje): self
    {
        $this->mensaje = $mensaje;

        return $this;
    }
}
