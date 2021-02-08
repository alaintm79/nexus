<?php

namespace App\Controller\Traits;

use App\Entity\Sistema\Usuario;
use App\Util\UsuarioUtil;
use phpseclib\Net\SSH2;
use Symfony\Component\Process\Process;

trait PasswordTrait {

    /**
     *  Establecer clave del usuario
     */
    private function userPassword($usuario, $password, $encoder)
    {
        $usuario->setPassword($encoder->encodePassword($usuario, $password));

        // Sincronización clave con dominio
        if($this->getParameter('app_pass_sync') === 'domain'){
            $usuario->setIsSyncPassword($this->syncPasswordDomain($usuario->getUsername(), $password));
        }
    }

    /**
     * Sincronización de clave con dominio
     */
    private function syncPasswordDomain($usuario, $clave): ?bool
    {
        if($this->isHostAlive($this->getParameter('app_ssh2_host'))){
            $ssh = new SSH2($this->getParameter('app_ssh2_host'));
            $cmd = "echo %s | sudo /usr/bin/samba-tool user setpassword %s --newpassword='%s'";

            $ssh->login($this->getParameter('app_ssh2_user'), $this->getParameter('app_ssh2_pass'));
            $ssh->exec(sprintf($cmd, $this->getParameter('app_ssh2_pass'), $usuario, $clave));

            return $ssh->getExitStatus() !== 0 ? false : true;
        }

        return false;
    }

    /**
     *  Comprobar si el host esta activo
     */
    private function isHostAlive ($ip): bool
    {
        $exec = 'ping -c 1 -W 1 '.$ip.' >/dev/null';

        $process = new Process($exec);
        $process->run();

        return $process->isSuccessful();
    }

}
