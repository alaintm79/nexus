<?php

namespace App\Repository\Blog;

use App\Entity\Blog\Directorio;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;


/**
 * @method Directorio|null find($id, $lockMode = null, $lockVersion = null)
 * @method Directorio|null findOneBy(array $criteria, array $orderBy = null)
 * @method Directorio[]    findAll()
 * @method Directorio[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DirectorioRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Directorio::class);
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
