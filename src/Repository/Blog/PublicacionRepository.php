<?php

namespace App\Repository\Blog;

use App\Entity\Blog\Publicacion;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;

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

    public function findTotalesByEstado(): ?array
    {

        $totales = $this->createQueryBuilder('p')
            ->select("SUM(( CASE WHEN( e.estado = 'Publicado' )  THEN 1 ELSE 0 END )) AS publicados")
            ->addSelect("SUM(( CASE WHEN( e.estado = 'Borrador' )  THEN 1 ELSE 0 END )) AS borradores")
            ->addSelect("SUM(( CASE WHEN( e.estado = 'Eliminado' )  THEN 1 ELSE 0 END )) AS eliminados")
            ->leftJoin('p.estado', 'e')
            ->getQuery()
            ->useQueryCache(true)
            ->getArrayResult();

        return $totales[0];
    }

    public function findAllPublicacionesWithQueryBuilder(string $estado): ?QueryBuilder
    {
        return $this->createQueryBuilder('p')
            ->select('p.id, p.titulo, p.fechaPublicacion, p.resumen, p.contenido, p.slug, p.thumbnail, p.isSticky')
            ->addSelect('c.categoria, e.estado')
            ->addSelect("CONCAT(a.nombre, ' ', a.apellidos) AS autor")
            ->leftJoin('p.estado', 'e')
            ->leftJoin('p.categoria', 'c')
            ->leftJoin('p.autor', 'a')
            ->where('e.estado = :estado')
            ->setParameter('estado', $estado)
            ->orderBy('p.isSticky', 'DESC')
            ->addOrderBy('p.fechaPublicacion', 'DESC')
            ->addOrderBy('p.id', 'DESC');
    }

    public function findPublicacionesByEstado(string $estado): ?array
    {
        return $this->createQueryBuilder('p')
            ->select('p.id, p.titulo, p.fechaPublicacion, p.isSticky, p.slug, p.thumbnail, p.isSticky')
            ->addSelect('c.categoria, e.estado')
            ->addSelect("CONCAT(a.nombre, ' ', a.apellidos) AS autor")
            ->leftJoin('p.estado', 'e')
            ->leftJoin('p.categoria', 'c')
            ->leftJoin('p.autor', 'a')
            ->where('e.estado = :estado')
            ->setParameter('estado', $estado)
            ->orderBy('p.isSticky', 'DESC')
            ->addOrderBy('p.fechaPublicacion', 'DESC')
            ->addOrderBy('p.id', 'DESC')
            ->getQuery()
            ->getScalarResult();
    }

    public function findPublicacionByIdAndSlug(int $id, string $slug, string $estado = 'Publicado'): ?array
    {
        return $this->createQueryBuilder('p')
            ->select('p.id, p.titulo, p.fechaPublicacion, p.contenido, p.thumbnail, p.isSticky')
            ->addSelect('c.categoria, e.estado')
            ->addSelect("CONCAT(a.nombre, ' ', a.apellidos) AS autor")
            ->leftJoin('p.estado', 'e')
            ->leftJoin('p.categoria', 'c')
            ->leftJoin('p.autor', 'a')
            ->where('p.id = :id')
            ->andWhere('p.slug = :slug')
            ->andWhere('e.estado = :estado')
            ->setParameter('id', $id)
            ->setParameter('slug', $slug)
            ->setParameter('estado', $estado)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findLatestOrRecommended(int $total, bool $stickyOnly = false): ?array
    {
        $qb = $this->createQueryBuilder('p');

        $qb->select('p.id, p.titulo, p.resumen, p.slug, e.estado, p.fechaPublicacion, p.isSticky, p.thumbnail')
            ->addSelect('c.categoria, e.estado')
            ->addSelect("CONCAT(a.nombre, ' ', a.apellidos) AS autor")
            ->leftJoin('p.estado', 'e')
            ->leftJoin('p.autor', 'a')
            ->leftJoin('p.categoria', 'c')
            ->where('e.estado = :estado')
            ->setParameter('estado', 'Publicado')
            ->setMaxResults($total)
            ->orderBy('p.fechaPublicacion', 'DESC')
            ->addOrderBy('p.id', 'DESC');

        if($stickyOnly){
            $qb->andWhere('p.isSticky = true');
        }

        return $qb->getQuery()
            ->getScalarResult();
    }
}
