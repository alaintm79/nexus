<?php

namespace App\Entity\System;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\System\UserRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: 'nexus.sys_users')]
#[UniqueEntity(fields: 'idCard', message: 'Carnet de identidad en uso!.')]
#[UniqueEntity(fields: 'username', message: 'Nombre de usuario en uso!.')]
#[UniqueEntity(fields: 'email', message: 'Cuenta de correo en uso!.')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 180, unique: true, nullable: true)]
    private $username;

    #[ORM\Column(type: 'string', nullable: true)]
    private $password;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank()]
    private $firstname;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank()]
    private $lastname;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private $isDeleted = false;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private $isDisabled = false;

    #[ORM\ManyToMany(targetEntity: Role::class, inversedBy: 'users')]
    #[ORM\JoinTable(name: 'nexus.sys_user_role')]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'role_id', referencedColumnName: 'id')]
    private $role;

    #[ORM\Column(type: 'string', length: 11, nullable: true)]
    #[Assert\NotBlank()]
    private $idCard;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $age;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Assert\Email()]
    private $email;

    #[ORM\ManyToOne(targetEntity: Unit::class)]
    private $unit;

    #[ORM\ManyToOne(targetEntity: Area::class)]
    private $area;

    #[ORM\ManyToOne(targetEntity: Job::class)]
    private $job;

    public function __construct()
    {
        $this->role = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): ?string
    {
        return (string) $this->username;
    }

    public function setUsername(?string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): ?string
    {
        return (string) $this->username;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $userRoles = $this->getRole();

        foreach ($userRoles as $userRole) {
            $roles[] = $userRole->getRole();
        }

        if(empty($roles)){
            $roles[] = 'ROLE_USER';
        }

        return array_unique($roles);

    }
    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): self
    {
        if($password !== null){
            $this->password = $password;
        }

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstName(): ?string
    {
        return $this->firstname;
    }

    public function setFirstName(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getIsDeleted(): ?bool
    {
        return $this->isDeleted;
    }

    public function setIsDeleted(?bool $isDeleted): self
    {
        $this->isDeleted = $isDeleted;

        return $this;
    }

    public function getIsDisabled(): ?bool
    {
        return $this->isDisabled;
    }

    public function setIsDisabled(?bool $isDisabled): self
    {
        $this->isDisabled = $isDisabled;

        return $this;
    }

    /**
     * @return Collection<int, Role>
     */
    public function getRole(): Collection
    {
        return $this->role;
    }

    public function addRole(Role $role): self
    {
        if (!$this->role->contains($role)) {
            $this->role[] = $role;
        }

        return $this;
    }

    public function removeRole(Role $role): self
    {
        $this->role->removeElement($role);

        return $this;
    }

    public function getIdCard(): ?string
    {
        return $this->idCard;
    }

    public function setIdCard(?string $idCard): self
    {
        $this->idCard = $idCard;

        return $this;
    }

    public function getAge(): ?int
    {
        return $this->age;
    }

    public function setAge(?int $age): self
    {
        $this->age = $age;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getUnit(): ?Unit
    {
        return $this->unit;
    }

    public function setUnit(?Unit $unit): self
    {
        $this->unit = $unit;

        return $this;
    }

    public function getArea(): ?Area
    {
        return $this->area;
    }

    public function setArea(?Area $area): self
    {
        $this->area = $area;

        return $this;
    }

    public function getJob(): ?Job
    {
        return $this->job;
    }

    public function setJob(?Job $job): self
    {
        $this->job = $job;

        return $this;
    }

    #[Assert\IsTrue(message: "Carnet de Identidad no válido")]
    public function isCiValid(): bool
    {
        if(\is_null($this->idCard)){
            return true;
        }

        $val = $this->idCard;
        $anno = $val[6] <= 5 ? '19'.substr($val, 0, 2) : '20'.substr($val, 0, 2);
        $mes = substr($val, 2, 2);
        $dia = substr($val, 4, 2);

        if($anno < 1940){
            return false;
        }

        return \checkdate($mes, $dia, $anno);
    }

    #[Assert\IsTrue(message: "Cuenta de usuario no definida")]
    public function isAccountValid(): bool
    {
        if((null !== $this->getPassword()
        || null !== $this->getRoles()
        || null !== $this->getEmail())
        && empty($this->getUserIdentifier())){

            return false;
        }
         return true;
    }
}
