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

    public function findArchivos(): ?array
    {
        return $this->createQueryBuilder('a')
            ->select("a.id, a.directorio, a.ruta, a.isActive")
            ->addSelect("COALESCE(a.observacion, '') AS observacion")
            ->orderBy('a.id', 'asc')
            ->getQuery()
            ->getArrayResult();
    }

    public function findArchivoById(int $id): ?Archivo
    {
        return $this->createQueryBuilder('a')
            ->where('a.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getSingleResult();
    }
}
