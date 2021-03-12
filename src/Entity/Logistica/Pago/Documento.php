<?php

namespace App\Entity\Logistica\Pago;

use App\Entity\Traits\IdTrait;
use App\Repository\Logistica\Pago\DocumentoRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="nexus.sp_tipos_documentos")
 * @ORM\Entity(repositoryClass=DocumentoRepository::class)
 */
class Documento
{
    use IdTrait;

    /**
     * @var string
     *
     * @ORM\Column(name="tipo", type="string", length=50, unique=true)
     */
    private $tipo;

    public function getTipo(): ?string
    {
        return $this->tipo;
    }

    public function setTipo(string $tipo): self
    {
        $this->tipo = $tipo;

        return $this;
    }

    /*
     *  __toString
     */
    public function __toString ()
    {
        return $this->getTipo();
    }
}
