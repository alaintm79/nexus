<?php

namespace App\Util;

use DateTime;

class UsuarioUtil
{
    /**
     *  Obtener el sexo mediante el CI
     */
    static function sexo(string $ci): string
    {
        return bcmod($ci[9], 2) === 0 ? 'M' : 'F';
    }

    /**
     *  Obtener la edad mediante el CI
     */
    static function edad(string $ci): string
    {
        $anno = $ci[6] <= 5 ? '19'.substr($ci, 0, 2) : '20'.substr($ci, 0, 2);
        $fecha = $anno."-".substr($ci, 2, 2)."-".substr($ci, 4, 2);
        $fechaNacimiento = new DateTime($fecha);
        $fechaActual = new DateTime();
        $edad = $fechaActual->diff($fechaNacimiento);

        return $edad->y;
    }
}
