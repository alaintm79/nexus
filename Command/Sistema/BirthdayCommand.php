<?php

namespace App\Command\Sistema;

use DateTime;
use App\Util\UsuarioUtil;
use App\Entity\Sistema\Usuario;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class BirthdayCommand extends Command
{
    protected static $defaultName = 'app:usuario:birthday-update';

    private $em;

    public function __construct(EntityManagerInterface $entityManagerInterface)
    {
        $this->entityManagerInterface = $entityManagerInterface;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Sistema \ Actualización de edad en los usuarios')
            ->addOption(
                'all',
                null,
                InputOption::VALUE_OPTIONAL,
                'Actualizar la edad de los usuaurios indistintamente de la fecha',
                false
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln([
            'Actualizando edad de los usuarios',
            ''
        ]);

        $fecha = new DateTime('now');
        $em = $this->entityManagerInterface;
        $usuarios = $em->getRepository(Usuario::class)->findByDateInCi($fecha->format('md'), $input->getOption('all'));

        foreach($usuarios as $usuario){
            $usuario->setEdad(UsuarioUtil::edad($usuario->getCi()));
        }

        $em->flush();

        $output->writeln('Actualizados: '.\count($usuarios).' usuarios');

        return 0;
    }
}
