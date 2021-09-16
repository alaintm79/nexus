<?php

namespace App\Repository\Blog;

use App\Entity\Blog\Archivo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;


/**
 * @method Archivo|null find($id, $lockMode = null, $lockVersion = null)
 * @method Archivo|null findOneBy(array $criteria, array $orderBy = null)
 * @method Archivo[]    findAll()
 * @method Archivo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArchivoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Archivo::class);
    }

    public function findAll(): ?array
    {
        return $this->createQueryBuilder('a')
            ->select("a.id, a.nombre, a.ruta, a.isActive")
            ->addSelect("COALESCE(a.observacion, '') AS observacion")
            ->orderBy('a.id', 'asc')
            ->getQuery()
            ->getScalarResult();
    }

    public function findByIsActive(): ?array
    {
        return $this->createQueryBuilder('a')
            ->select("a.id, a.nombre, a.ruta, a.isActive")
            ->orderBy('a.nombre', 'asc')
            ->where('a.isActive = true')
            ->getQuery()
            ->getScalarResult();
    }

    public function findByRuta(string $ruta): ?array
    {
        return $this->createQueryBuilder('a')
            ->select('a.id, a.nombre, a.ruta')
            ->where('a.ruta = :ruta')
            ->setParameter('ruta', $ruta)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
