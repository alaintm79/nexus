<?php

namespace App\Entity\Sistema;

use App\Entity\Traits\BajaTrait;
use App\Entity\Traits\IdTrait;
use App\Entity\Traits\IsActiveTrait;
use App\Entity\Traits\ObservacionTrait;
use App\Entity\Traits\TimeStampableTrait;
use App\Repository\Sistema\UsuarioRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * @ORM\Table(name="nexus.nx_usuarios")
 * @ORM\Entity(repositoryClass=UsuarioRepository::class)
 * @UniqueEntity(fields={"ci"}, message="CI en uso!.")
 * @UniqueEntity(fields={"username"}, message="Nombre de usuario en uso!.")
 * @UniqueEntity(fields={"correo"}, message="Cuenta de correo en uso!.")
 */
class Usuario implements UserInterface
{
    use IdTrait, IsActiveTrait, TimeStampableTrait, BajaTrait, ObservacionTrait;

    /**
     * @ORM\Column(type="string", length=180, unique=true, nullable=true)
     */
    private $username;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=50)
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="apellidos", type="string", length=150)
     */
    private $apellidos;

    /**
     * @var string
     *
     * @ORM\Column(name="ci", type="string", length=11, unique=true)
     */
    private $ci;

    /**
     * @var int
     *
     * @ORM\Column(name="edad", type="integer", nullable=true)
     */
    private $edad;

    /**
     * @var string
     *
     * @ORM\Column(name="sexo", type="string", length=1, nullable=true)
     */
    private $sexo;

    /**
     * @var string
     *
     * @ORM\Column(name="correo", type="string", length=50, unique=true, nullable=true)
     */
    private $correo;

    /**
     * @var bool
     *
     * @ORM\Column(name="has_account", type="boolean", nullable=true)
     */
    private $hasAccount = false;

    /**
     * @ORM\ManyToOne(targetEntity=Unidad::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $unidad;

    /**
     * @ORM\ManyToOne(targetEntity=Plaza::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $plaza;

    /**
     * @ORM\Column(name="servicios", type="json_array", nullable=true)
     */
    private $servicio = array();

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_alta", type="datetime", nullable=true)
     */
    private $fechaAlta;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_sync_password", type="boolean", nullable=true)
     */
    private $isSyncPassword;

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    public function setUsername(?string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getApellidos(): ?string
    {
        return $this->apellidos;
    }

    public function setApellidos(string $apellidos): self
    {
        $this->apellidos = $apellidos;

        return $this;
    }

    public function getCi(): ?string
    {
        return $this->ci;
    }

    public function setCi(?string $ci): self
    {
        $this->ci = $ci;

        return $this;
    }

    public function getEdad(): ?int
    {
        return $this->edad;
    }

    public function setEdad(?int $edad): self
    {
        $this->edad = $edad;

        return $this;
    }

    public function getSexo(): ?string
    {
        return $this->sexo;
    }

    public function setSexo(?string $sexo): self
    {
        $this->sexo = $sexo;

        return $this;
    }

    public function getCorreo(): ?string
    {
        return $this->correo;
    }

    public function setCorreo(?string $correo): self
    {
        $this->correo = $correo;

        return $this;
    }

    public function getHasAccount(): ?bool
    {
        return $this->hasAccount;
    }

    public function setHasAccount(?bool $hasAccount): self
    {
        $this->hasAccount = $hasAccount;

        return $this;
    }

    public function getUnidad(): ?Unidad
    {
        return $this->unidad;
    }

    public function setUnidad(?Unidad $unidad): self
    {
        $this->unidad = $unidad;

        return $this;
    }

    public function getPlaza(): ?Plaza
    {
        return $this->plaza;
    }

    public function setPlaza(?Plaza $plaza): self
    {
        $this->plaza = $plaza;

        return $this;
    }

    public function getServicio(): ?array
    {
        return $this->servicio;
    }

    public function setServicio(?array $servicio): self
    {
        $this->servicio = $servicio;

        return $this;
    }

    public function getFechaAlta(): ?\DateTimeInterface
    {
        return $this->fechaAlta;
    }

    public function setFechaAlta(?\DateTimeInterface $fechaAlta): self
    {
        $this->fechaAlta = $fechaAlta;

        return $this;
    }

    public function getIsSyncPassword(): ?bool
    {
        return $this->isSyncPassword;
    }

    public function setIsSyncPassword(?bool $isSyncPassword): self
    {
        $this->isSyncPassword = $isSyncPassword;

        return $this;
    }

    /*
     *  __toString
     */

    public function __toString ()
    {
        return $this->nombre.' '.$this->apellidos;
    }
}
