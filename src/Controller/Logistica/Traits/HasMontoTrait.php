<?php

namespace App\Controller\Logistica\Traits;

use App\Entity\Logistica\Pago\Solicitud;

trait HasMontoTrait
{
    public function hasMontoEjecucion(Solicitud $solicitud): bool
    {
        $contrato = $solicitud->getContrato();

        if((null !== $contrato->getValorCup()) && ($solicitud->getImporteCup() > $contrato->getValorEjecucionCup())) {
            return false;
        }

        return true;
    }
}
