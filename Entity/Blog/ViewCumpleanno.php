<?php

namespace App\Entity\Blog;

use App\Entity\Traits\IdTrait;
use App\Repository\Blog\EstadoRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * PublicacionEstado
 *
 * @ORM\Table(name="nexus.view_blog_cumpleannos")
 * @ORM\Entity(repositoryClass=EstadoRepository::class, readOnly=true)
 */
class ViewCumpleanno
{
    use IdTrait;

    /**
     * @var string
     *
     * @ORM\Column(name="usuario", type="string", length=255)
     */
    private $usuario;

    /**
     * @var string
     *
     * @ORM\Column(name="unidad", type="string", length=255)
     */
    private $unidad;

    /**
     * @var int
     *
     * @ORM\Column(name="edad", type="integer")
     */
    private $edad;

    /**
     * @var int
     *
     * @ORM\Column(name="dia", type="integer")
     */
    private $dia;

    /**
     * @var int
     *
     * @ORM\Column(name="mes", type="integer")
     */
    private $mes;

    /**
     * @var int
     *
     * @ORM\Column(name="anno", type="integer")
     */
    private $anno;

}

