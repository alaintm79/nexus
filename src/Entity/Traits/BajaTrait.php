<?php

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;

trait BajaTrait {

    /**
     * @var bool
     *
     * @ORM\Column(name="is_baja", type="boolean", nullable=true)
     */
    private $isBaja = false;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_baja", type="datetime", nullable=true)
     */
    private $fechaBaja;

    public function getIsBaja(): ?bool
    {
        return $this->isBaja;
    }

    public function setIsBaja(?bool $isBaja): self
    {
        $this->isBaja = $isBaja;

        return $this;
    }

    public function getFechaBaja(): ?\DateTimeInterface
    {
        return $this->fechaBaja;
    }

    public function setFechaBaja(?\DateTimeInterface $fechaBaja): self
    {
        $this->fechaBaja = $fechaBaja;

        return $this;
    }

}
