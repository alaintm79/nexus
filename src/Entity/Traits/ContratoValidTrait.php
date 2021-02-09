<?php

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

trait ContratoValidTrait {

    /**
     * @Assert\IsTrue(
     *      message = "Falta el número de comite.",
     *      groups={"new", "edit"}
     * )
     */
    public function isRegistroComiteValid(): bool
    {
        if($this->getEstado()->getEstado() !== 'REVISION' && $this->getTipo() === 'p'){
            if(is_null($this->getRegistroComite())){
                return false;
            }
        }
    }

    /**
     * @Assert\IsTrue(
     *      message = "Falta el número de acuerdo.",
     *      groups={"new", "edit"}
     * )
     */
    public function isRegistroAcuerdoValid(): bool
    {
        if($this->getEstado()->getEstado() !== 'REVISION' && $this->getTipo() === 'p'){
            if(is_null($this->getRegistroAcuerdo())){
                return false;
            }
        }
    }

    /**
     * @Assert\IsTrue(
     *      message = "Falta el número de comite.",
     *      groups={"edit"}
     * )
     */
    public function isCanceladoComiteValid()
    {
        if($this->getEstado()->getEstado() === 'CANCELADO' && $this->getTipo() !== 'c'){
            if(is_null($this->getCanceladoComite())){
                return false;
            }
        }
    }

    /**
     * @Assert\IsTrue(
     *      message = "Fecha fuera de rango.",
     *      groups={"edit"}
     * )
     */
    public function isFechaFirmaValid()
    {
        if($this->getEstado()->getEstado() === 'FIRMADO'){
            if($this->getFechaAprobado() > $this->getFechaFirma()){
                return false;
            }
        }
    }

    /**
     * @Assert\IsTrue(
     *      message = "Fecha de firma fuera de rango.",
     *      groups={"edit"}
     * )
     */
    public function isFechaCanceladoValid()
    {
        if($this->getEstado()->getEstado() === 'CANCELADO'){
            if($this->getFechaAprobado() > $this->getFechaCancelado()){
                return false;
            }
        }
    }


}
