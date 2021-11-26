<?php

namespace App\Entity\Tic\Linea;

use App\Entity\Traits\IdTrait;
use App\Entity\Traits\TimeStampableTrait;
use App\Repository\Tic\Linea\LogSimRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="nexus.tic_lineas_log_sim")
 * @ORM\Entity(repositoryClass=LogSimRepository::class)
 */
class LogSim
{
    use IdTrait, TimeStampableTrait;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $pin;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $puk;

    /**
     * @ORM\ManyToOne(targetEntity=Linea::class, inversedBy="logSim")
     */
    private $linea;

    public function getPin(): ?string
    {
        return $this->pin;
    }

    public function setPin(string $pin): self
    {
        $this->pin = $pin;

        return $this;
    }

    public function getPuk(): ?string
    {
        return $this->puk;
    }

    public function setPuk(string $puk): self
    {
        $this->puk = $puk;

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
