<?php

namespace App\Entity\Buzon;

use App\Repository\Buzon\TipoMensajeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="nexus.buzon_tipo_mensaje")
 * @ORM\Entity(repositoryClass=TipoMensajeRepository::class)
 */
class TipoMensaje
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(name="tipo_mensaje", type="string", length=255)
     */
    private $tipoMensaje;

    /**
     * @ORM\OneToMany(targetEntity=Mensaje::class, mappedBy="tipoMensaje")
     */
    private $mensaje;

    public function __construct()
    {
        $this->mensaje = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTipoMensaje(): ?string
    {
        return $this->tipoMensaje;
    }

    public function setTipoMensaje(string $tipoMensaje): self
    {
        $this->tipoMensaje = $tipoMensaje;

        return $this;
    }

    /**
     * @return Collection|Mensaje[]
     */
    public function getMensaje(): Collection
    {
        return $this->mensaje;
    }

    public function addMensaje(Mensaje $mensaje): self
    {
        if (!$this->mensaje->contains($mensaje)) {
            $this->mensaje[] = $mensaje;
            $mensaje->setTipoMensaje($this);
        }

        return $this;
    }

    public function removeMensaje(Mensaje $mensaje): self
    {
        if ($this->mensaje->removeElement($mensaje)) {
            // set the owning side to null (unless already changed)
            if ($mensaje->getTipoMensaje() === $this) {
                $mensaje->setTipoMensaje(null);
            }
        }

        return $this;
    }

    /*
     *  __toString
     */
    public function __toString ()
    {
        return \ucfirst($this->tipoMensaje);
    }
}
