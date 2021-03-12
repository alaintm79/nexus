<?php

namespace App\Repository\Blog;

use App\Entity\Blog\Publicacion;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;


/**
 * @method Publicacion|null find($id, $lockMode = null, $lockVersion = null)
 * @method Publicacion|null findOneBy(array $criteria, array $orderBy = null)
 * @method Publicacion[]    findAll()
 * @method Publicacion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PublicacionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Publicacion::class);
    }

    public function findTotales(): ?array
    {

        $totales = $this->createQueryBuilder('p')
            ->select("SUM(( CASE WHEN( e.estado = 'publicado' )  THEN 1 ELSE 0 END )) AS publicados")
            ->addSelect("SUM(( CASE WHEN( e.estado = 'borrador' )  THEN 1 ELSE 0 END )) AS borradores")
            ->addSelect("SUM(( CASE WHEN( e.estado = 'eliminado' )  THEN 1 ELSE 0 END )) AS eliminados")
            ->leftJoin('p.estado', 'e')
            ->getQuery()
            ->useQueryCache(true)
            ->getArrayResult();

        return $totales[0];
    }

    public function findPublicacionesByEstado(string $estado): ?array
    {
        return $this->createQueryBuilder('p')
            ->select('p.titulo, a.usuario AS autor, c.categoria, e.estado, p.fechaPublicacion')
            ->leftJoin('p.estado', 'e')
            ->leftJoin('p.categoria', 'c')
            ->leftJoin('p.autor', 'a')
            ->where('e.estado = :estado')
            ->setParameter('estado', $estado)
            ->orderBy('p.fechaPublicacion', 'DESC')
            ->addOrderBy('p.id', 'DESC')
            ->getQuery()
            ->getArrayResult();
    }


    public function findLatest(): ?Publicacion
    {
        return $this->createQueryBuilder('p')
                ->where('p.estado = :estado')
                ->setMaxResults(4)
                ->setParameter('estado', 'publicado')
                ->orderBy('p.fechaPublicacion', 'DESC')
                ->addOrderBy('p.id', 'DESC')
                ->getQuery()
                ->getResult();
    }
}
