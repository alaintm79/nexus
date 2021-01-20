<?php

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;

trait ObservacionTrait {

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $observacion;

    public function getObservacion(): ?string
    {
        return $this->observacion;
    }

    public function setObservacion(?string $observacion): self
    {
        $this->observacion = $observacion;

        return $this;
    }

}
