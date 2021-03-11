<?php

namespace App\Command\Logistica;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ContratosVigenciaCommand extends Command
{
    protected static $defaultName = 'app:logistica:contrato:vigencia';

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;

        parent::__construct();
    }

    protected function configure()
    {
        $this->setDescription('Logistica \ Actualizar vigencia de contratos');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln([
            'Actualizando vigencia de contratos...',
            '',
        ]);

        /*
         * id 4 = FIRMADO
         * id 34 = SIN VIGENCIA
         */

        $q = $this->em->createQuery('
            update App\Entity\Logistica\Contrato\Contrato c set c.estado = 6
            where c.estado = 4 and (DATE_DIFF(c.fechaVigencia, CURRENT_DATE()) < 0)
        ');

        $count = $q->execute();

        $output->writeln("Un total de <info> $count </info> contratos actualizados :) ");

        return 0;
    }
}
