<?php

namespace App\Repository\Blog;

use App\Entity\Blog\Comentario;
use App\Repository\Traits\BootstrapTableTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Comentario|null find($id, $lockMode = null, $lockVersion = null)
 * @method Comentario|null findOneBy(array $criteria, array $orderBy = null)
 * @method Comentario[]    findAll()
 * @method Comentario[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ComentarioRepository extends ServiceEntityRepository
{
    use BootstrapTableTrait;

    private const COLUMNS = [
        'comentario' => 'c.comentario',
        'titulo' => 'p.titulo',
        'fechaCreado' => 'c.fechaCreado',
        'username' => 'u.username'
    ];

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Comentario::class);
    }

    public function findComentariosByPublicacionId(int $id): ?array
    {
        return $this->createQueryBuilder('c')
            ->where('c.publicacion = :id')
            ->andWhere('c.isReview = true')
            ->setParameter('id', $id)
            ->orderBy('c.fechaCreado', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findComentariosByEstado(array $params, string $estado): ?array
    {
        $qb = $this->createQueryBuilder('c')
            ->select('c.id, c.comentario, c.fechaCreado, c.isDelete, c.isReview')
            ->addSelect('p.titulo')
            ->addSelect('u.username')
            ->leftJoin('c.publicacion', 'p')
            ->leftJoin('c.usuario', 'u')
            ->setFirstResult($params['offset'])
            ->setMaxResults($params['limit'])
        ;

        $this->search($qb, self::COLUMNS, $params);

        if('aprobado' === $estado){
            $qb->andWhere('c.isReview = true')
                ->andWhere('c.isDelete = false');
        }elseif('pendiente' === $estado){
            $qb->andWhere('c.isReview = false')
                ->andWhere('c.isDelete = false');
        }elseif('eliminado' === $estado){
            $qb->andWhere('c.isDelete = true');
        }

        $this->sort($qb, self::COLUMNS, $params);

        return  $qb->getQuery()
                    ->getScalarResult();
    }


    public function findTotalComentariosByEstado(array $params, string $estado): ?int
    {
        $qb = $this->createQueryBuilder('c')
            ->select('COUNT(c.id)')
            ->leftJoin('c.publicacion', 'p')
            ->leftJoin('c.usuario', 'u')
        ;

        $this->search($qb, self::COLUMNS, $params);

        if('aprobado' === $estado){
            $qb->andWhere('c.isReview = true')
                ->andWhere('c.isDelete = false');
        }elseif('pendiente' === $estado){
            $qb->andWhere('c.isReview = false')
                ->andWhere('c.isDelete = false');
        }elseif('eliminado' === $estado){
            $qb->andWhere('c.isDelete = true');
        }

        return  $qb->getQuery()
                    ->getSingleScalarResult();
    }

    public function findTotalesByEstado(): ?array
    {
        $totales = $this->createQueryBuilder('c')
            ->select("SUM(( CASE WHEN( c.isReview = true AND c.isDelete = false )  THEN 1 ELSE 0 END )) AS aprobados")
            ->addSelect("SUM(( CASE WHEN( c.isReview = false AND c.isDelete = false )  THEN 1 ELSE 0 END )) AS pendientes")
            ->addSelect("SUM(( CASE WHEN( c.isDelete = true )  THEN 1 ELSE 0 END )) AS eliminados")
            ->getQuery()
            ->useQueryCache(true)
            ->getArrayResult();

        return $totales[0];
    }
}
