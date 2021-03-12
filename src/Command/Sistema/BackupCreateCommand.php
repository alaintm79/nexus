<?php

namespace App\Command\Sistema;

use Symfony\Component\Process\Process;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Exception\ProcessFailedException;

class BackupCreateCommand extends Command
{
    protected static $defaultName = 'app:backup:create';

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;

        parent::__construct();
    }

    protected function configure()
    {
        $this->setDescription('Sistema \ Respaldo de la BD del sistema');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln([
            'Creando backup del sistema...',
            '',
        ]);

        $exec = '/bin/bash '.__DIR__.'/../../../bin/pgdump.sh';
        $process = new Process($exec);

        $process->run();

        if (!$process->isSuccessful()) {
            return 1;
        }

        $output->writeln("Respaldo de la BD del sistema ejecutado con exito!");

        return 0;
    }
}
