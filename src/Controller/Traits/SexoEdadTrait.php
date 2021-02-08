<?php

namespace App\Controller\Traits;

use App\Entity\Sistema\Usuario;
use App\Util\UsuarioUtil;

trait SexoEdadTrait {

    /**
     *  Establecer sexo y edad
     */
    private function setSexoAndEdad(Usuario $usuario)
    {
        $usuario->setEdad(UsuarioUtil::edad($usuario->getCi()));
        $usuario->setSexo(UsuarioUtil::sexo($usuario->getCi()));
    }

}
