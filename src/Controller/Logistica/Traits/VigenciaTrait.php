<?php

namespace App\Controller\Logistica\Traits;

trait VigenciaTrait
{
    public function vigenciaFormat(string $vigencia): ?string
    {
        switch($vigencia){
            case '6 MESES':
                $vigencia = '+6 month';
                break;
            case '1 AÑO':
                $vigencia = '+1 years';
                break;
            case '2 AÑOS':
                $vigencia = '+2 years';
                break;
            case '3 AÑOS':
                $vigencia = '+3 years';
                break;
            case '4 AÑOS':
                $vigencia = '+4 years';
                break;
            case '5 AÑOS':
                $vigencia = '+5 years';
                break;
            default:
                return null;
        }

        return $vigencia;
    }
}
