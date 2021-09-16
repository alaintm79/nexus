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
            ->select("SUM(( CASE WHEN( e.estado = 'publicado' and p.isDelete = false )  THEN 1 ELSE 0 END )) AS publicados")
            ->addSelect("SUM(( CASE WHEN( e.estado = 'borrador' and p.isDelete = false )  THEN 1 ELSE 0 END )) AS borradores")
            ->addSelect("SUM(( CASE WHEN( e.estado = 'programado' and p.isDelete = false )  THEN 1 ELSE 0 END )) AS programados")
            ->addSelect("SUM(( CASE WHEN( p.isDelete = true )  THEN 1 ELSE 0 END )) AS eliminados")
            ->leftJoin('p.estado', 'e')
            ->getQuery()
            ->useQueryCache(true)
            ->getArrayResult();

        return $totales[0];
    }

    public function findAllPublicacionesWithQueryBuilder(string $estado): ?QueryBuilder
    {
        $qb = $this->createQueryBuilder('p')
            ->select('p.id, p.titulo, p.fechaPublicacion, p.resumen, p.contenido, p.slug, p.thumbnail, p.isSticky, p.isDelete')
            ->addSelect('c.categoria, e.estado')
            ->addSelect("CONCAT(a.nombre, ' ', a.apellidos) AS autor")
            ->leftJoin('p.estado', 'e')
            ->leftJoin('p.categoria', 'c')
            ->leftJoin('p.autor', 'a')
            ->orderBy('p.isSticky', 'DESC')
            ->addOrderBy('p.fechaPublicacion', 'DESC')
            ->addOrderBy('p.id', 'DESC');

            if($estado !== 'eliminado'){
                $qb->where('e.estado = :estado')
                    ->andWhere('p.isDelete = false')
                    ->setParameter('estado', $estado);
            } else {
                $qb->where('p.isDelete = true');
            }

            return  $qb;
    }

    public function findPublicacionesByEstado(string $estado): ?array
    {
        $qb = $this->createQueryBuilder('p')
            ->select('p.id, p.titulo, p.fechaPublicacion, p.isSticky, p.slug, p.thumbnail, p.isSticky, p.isDelete')
            ->addSelect('c.categoria, e.estado')
            ->addSelect("CONCAT(a.nombre, ' ', a.apellidos) AS autor")
            ->addSelect('count(cm.id) AS comentarios')
            ->addSelect('count(cp.id) AS visitas')
            ->leftJoin('p.estado', 'e')
            ->leftJoin('p.categoria', 'c')
            ->leftJoin('p.autor', 'a')
            ->leftJoin('p.comentarios', 'cm')
            ->leftJoin('p.counter', 'cp')
            ->orderBy('p.isSticky', 'DESC')
            ->addOrderBy('p.fechaPublicacion', 'DESC')
            ->addOrderBy('p.id', 'DESC')
            ->groupBy('p.id, c.id, e.id, a.id');

            if($estado !== 'eliminado'){
                $qb->where('e.estado = :estado')
                    ->andWhere('p.isDelete = false')
                    ->setParameter('estado', $estado);
            } else {
                $qb->where('p.isDelete = true');
            }

            return  $qb->getQuery()
                        ->getScalarResult();
    }

    public function findPublicacionByIdAndSlug(int $id, string $slug): ?array
    {
        $publicacion = $this->createQueryBuilder('p')
            ->select('p.id, p.resumen, p.titulo, p.contenido, p.fechaPublicacion, p.isSticky')
            ->addSelect('p.tomadoDeTitulo, p.tomadoDeUrl, p.isActiveComentarios, p.thumbnail')
            ->addSelect("CONCAT(a.nombre, ' ', a.apellidos) AS autor")
            ->addSelect('c.categoria')
            ->addSelect('e.estado')
            ->leftJoin('p.estado', 'e')
            ->leftJoin('p.categoria', 'c')
            ->leftJoin('p.autor', 'a')
            ->leftJoin('p.comentarios', 'cm')
            ->where('p.id = :id')
            ->andWhere('p.slug = :slug')
            ->andWhere('p.isDelete = false')
            ->groupBy('p.id, a.id, c.id, cm.id, e.id')
            ->setParameter('id', $id)
            ->setParameter('slug', $slug)
            ->getQuery()
            ->getScalarResult();

        return $publicacion[0];
    }

    public function findLatestOrRecommended(int $total, bool $stickyOnly = false): ?array
    {
        $qb = $this->createQueryBuilder('p');

        $qb->select('p.id, p.titulo, p.resumen, p.slug, e.estado, p.fechaPublicacion, p.isSticky, p.thumbnail, p.isDelete')
            ->addSelect('c.categoria, e.estado')
            ->addSelect("CONCAT(a.nombre, ' ', a.apellidos) AS autor")
            ->leftJoin('p.estado', 'e')
            ->leftJoin('p.autor', 'a')
            ->leftJoin('p.categoria', 'c')
            ->where('e.estado = :estado')
            ->andWhere('p.isDelete = false')
            ->setParameter('estado', 'publicado')
            ->setMaxResults($total)
            ->orderBy('p.isSticky', 'DESC')
            ->addOrderBy('p.fechaPublicacion', 'DESC')
            ->addOrderBy('p.id', 'DESC');

        if($stickyOnly){
            $qb->andWhere('p.isSticky = true');
        }

        return $qb->getQuery()
            ->getScalarResult();
    }

    public function findPublicacionesInQueue(): ?array
    {
        $date = new \DateTime('now');

        return $this->createQueryBuilder('p')
                ->select('p, e')
                ->leftJoin('p.estado', 'e')
                ->where('e.estado = :estado')
                ->andWhere('p.fechaPublicacion <= :date')
                ->setParameter('estado', 'programado')
                ->setParameter('date', $date->format('Y-m-d H:i:s'))
                ->getQuery()
                ->getResult()
            ;
    }

    public function findMoreViews(int $total): ?array
    {
        $qb = $this->createQueryBuilder('p');

        $qb->select('p.id, p.titulo, p.resumen, p.slug, e.estado, p.fechaPublicacion, p.isSticky, p.thumbnail, p.isDelete')
            ->addSelect('c.categoria, e.estado')
            ->addSelect("CONCAT(a.nombre, ' ', a.apellidos) AS autor")
            ->addSelect('count(cp.id) AS visitas')
            ->leftJoin('p.estado', 'e')
            ->leftJoin('p.autor', 'a')
            ->leftJoin('p.categoria', 'c')
            ->leftJoin('p.counter', 'cp')
            ->where('e.estado = :estado')
            ->andWhere('p.isDelete = false')
            ->addOrderBy('count(cp.id)', 'DESC')
            ->groupBy('p.id, a.id, c.id, e.id')
            ->setParameter('estado', 'publicado')
            ->setMaxResults($total);

        return $qb->getQuery()
            ->getScalarResult();
    }
}
