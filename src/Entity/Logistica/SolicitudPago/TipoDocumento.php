<?php

namespace App\Entity\Logistica\SolicitudPago;

use App\Entity\Traits\IdTrait;
use App\Repository\Logistica\SolicitudPago\TipoDocumentoRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="nexus.sp_tipos_documentos")
 * @ORM\Entity(repositoryClass=TipoDocumentoRepository::class)
 */
class TipoDocumento
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
}
