<?php

namespace App\Repository\Contacto;

use App\Entity\Contacto\Ubicacion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Ubicacion|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ubicacion|null findOneBy(array $criteria, array $orderBy = null)
 * @method Ubicacion[]    findAll()
 * @method Ubicacion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UbicacionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ubicacion::class);
    }

    public function findAll(): array
    {

        return $this->createQueryBuilder('u')
            ->select("u.id, u.nombre AS ubicacion")->getQuery()
            ->getScalarResult();
    }

    public function findReporteTotal(): int
    {
        return $this->createQueryBuilder('u')
            ->select('count(u.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }
}
