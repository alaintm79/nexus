<?php

namespace App\Entity\Blog;

use App\Entity\Traits\IdTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * PublicacionCategoria
 *
 * @ORM\Table(name="nexus.blog_categorias")
 * @ORM\Entity(repositoryClass=CategoriaRepository::class)
 */
class Categoria
{
    use IdTrait;

    /**
     * @var string
     *
     * @ORM\Column(name="categoria", type="string", length=255, unique=true)
     */
    private $categoria;

    public function getCategoria(): ?string
    {
        return $this->categoria;
    }

    public function setCategoria(string $categoria): self
    {
        $this->categoria = $categoria;

        return $this;
    }
}

