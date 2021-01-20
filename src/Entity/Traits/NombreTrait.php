<?php

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;

trait NombreTrait {

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $nombre;

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

}
