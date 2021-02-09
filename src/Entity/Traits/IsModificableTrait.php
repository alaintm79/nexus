<?php

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;

trait IsModificableTrait {

    /**
     * @var bool
     *
     * @ORM\Column(name="is_modificable", type="boolean", nullable=true)
     */
    private $isModificable = true;

    public function getIsModificable(): ?bool
    {
        return $this->isModificable;
    }

    public function setIsModificable(?bool $isModificable): self
    {
        $this->isModificable = $isModificable;

        return $this;
    }

}
