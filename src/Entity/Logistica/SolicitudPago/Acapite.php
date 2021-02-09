<?php

namespace App\Entity\Logistica\SolicitudPago;

use App\Entity\Traits\IdTrait;
use App\Repository\Logistica\SolicitudPago\AcapiteRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="nexus.sp_acapites")
 * @ORM\Entity(repositoryClass=AcapiteRepository::class)
 */
class Acapite
{
    use IdTrait;

    /**
     * @var string
     *
     * @ORM\Column(name="acapite", type="string", length=255)
     */
    private $acapite;

    public function getAcapite(): ?string
    {
        return $this->acapite;
    }

    public function setAcapite(string $acapite): self
    {
        $this->acapite = $acapite;

        return $this;
    }

    /*
     *  __toString
     */
    public function __toString ()
    {
        return $this->acapite;
    }
}
