<?php

namespace App\Entity\Sistema;

use App\Entity\Traits\BajaTrait;
use App\Entity\Traits\IdTrait;
use App\Entity\Traits\IsActiveTrait;
use App\Entity\Traits\ObservacionTrait;
use App\Entity\Traits\TimeStampableTrait;
use App\Repository\Sistema\UsuarioRepository;
use App\Util\UsuarioUtil;
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
     * @Assert\Regex(
     *     pattern     = "/^[a-z0-9]+$/i",
     *     htmlPattern = "^[a-z0-9]+$",
     *     message="Este valor no es válido.",
     *     groups={"registration"}
     * )
     */
    private $username;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string", nullable=true)
     */
    private $password;

    /**
     * @ORM\Column(name="nombre", type="string", length=50)
     * @Assert\NotBlank(groups={"registration"})
     * @Assert\Regex(
     *     pattern     = "/^[a-zñáéíóú ]+$/i",
     *     htmlPattern = "^[a-zñáéíóú ]+$",
     *     message="Este valor no es válido."
     * )
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="apellidos", type="string", length=150)
     * @Assert\NotBlank(groups={"registration"})
     * @Assert\Regex(
     *     pattern     = "/^[a-zñáéíóú ]+$/i",
     *     htmlPattern = "^[a-zñáéíóú ]+$",
     *     message="Este valor no es válido."
     * )
     */
    private $apellidos;

    /**
     * @var string
     *
     * @ORM\Column(name="ci", type="string", length=11, unique=true)
     * @Assert\Length(
     *      min = 11,
     *      max = 11,
     *      minMessage = "Valor no permitido {{ limit }} 1",
     *      maxMessage = "Valor no permitido {{ limit }} 2",
     *      allowEmptyString = false,
     *      groups = {"registration"}
     * )
     * @Assert\Regex(
     *     pattern     = "/^[0-9]+$/i",
     *     htmlPattern = "^[0-9]+$",
     *     message="Este valor no es válido."
     * )
     */
    private $ci;

    /**
     * @var int
     *
     * @ORM\Column(name="edad", type="integer")
     */
    private $edad;

    /**
     * @var string
     *
     * @ORM\Column(name="sexo", type="string", length=2)
     */
    private $sexo;

    /**
     * @var string
     *
     * @ORM\Column(name="correo", type="string", length=50, unique=true, nullable=true)
     * @Assert\Email(groups={"registration"})
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
     * @Assert\NotNull(groups={"registration"})
     */
    private $unidad;

    /**
     * @ORM\ManyToOne(targetEntity=Plaza::class)
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotNull(groups={"registration"})
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
     * @Assert\NotBlank(groups={"registration"})
     * @Assert\DateTime()
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
        $activeRoles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $activeRoles[] = 'ROLE_USER';

        return array_unique($activeRoles);
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

    public function setNombre(?string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getApellidos(): ?string
    {
        return $this->apellidos;
    }

    public function setApellidos(?string $apellidos): self
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
        $this->setEdad(UsuarioUtil::edad($ci));
        $this->setSexo(UsuarioUtil::sexo($ci));

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

    /**
     * @Assert\IsTrue(message="CI no valido", groups={"registration"})
     */
    public function isCiValid()
    {
        $ci = $this->ci;
        $anno = $ci[6] <= 5 ? '19'.substr($ci, 0, 2) : '20'.substr($ci, 0, 2);
        $mes = substr($ci, 2, 2);
        $dia = substr($ci, 4, 2);

        if($anno < 1940){
            return false;
        }

        return \checkdate($mes, $dia, $anno);
    }

    /*
     *  __toString
     */

    public function __toString ()
    {
        return $this->nombre.' '.$this->apellidos;
    }
}
