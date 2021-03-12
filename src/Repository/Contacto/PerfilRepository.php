<?php

namespace App\Repository\Contacto;

use App\Entity\Contacto\Perfil;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Perfil|null find($id, $lockMode = null, $lockVersion = null)
 * @method Perfil|null findOneBy(array $criteria, array $orderBy = null)
 * @method Perfil[]    findAll()
 * @method Perfil[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PerfilRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Perfil::class);
    }

    public function findAll(): array
    {

        return $this->createQueryBuilder('p')
            ->select("p.id, p.nombre AS perfil")
            ->getQuery()
            ->getScalarResult();
    }

    public function findReporteTotal(): int
    {
        return $this->createQueryBuilder('p')
            ->select('count(p.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }
}
