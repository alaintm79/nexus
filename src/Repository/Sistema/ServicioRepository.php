<?php

namespace App\Repository\Sistema;

use App\Entity\Sistema\Servicio;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Servicio|null find($id, $lockMode = null, $lockVersion = null)
 * @method Servicio|null findOneBy(array $criteria, array $orderBy = null)
 * @method Servicio[]    findAll()
 * @method Servicio[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ServicioRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Servicio::class);
    }

    public function findServicios()
    {
        $qb = $this->createQueryBuilder('s');
        $servicios = $qb->select('s')
            ->getQuery()
            ->getArrayResult();

        return array_column($servicios, 'servicio', 'servicio');
    }
}
