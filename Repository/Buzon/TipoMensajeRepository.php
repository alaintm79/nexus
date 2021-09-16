<?php

namespace App\Repository\Buzon;

use App\Entity\Buzon\TipoMensaje;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TipoMensaje|null find($id, $lockMode = null, $lockVersion = null)
 * @method TipoMensaje|null findOneBy(array $criteria, array $orderBy = null)
 * @method TipoMensaje[]    findAll()
 * @method TipoMensaje[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TipoMensajeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TipoMensaje::class);
    }
}
