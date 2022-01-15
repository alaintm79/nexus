<?php

namespace App\Repository\Tic\Celular;

use App\Entity\Tic\Celular\Celular;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use phpDocumentor\Reflection\Types\Void_;

/**
 * @method Celular|null find($id, $lockMode = null, $lockVersion = null)
 * @method Celular|null findOneBy(array $criteria, array $orderBy = null)
 * @method Celular[]    findAll()
 * @method Celular[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CelularRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Celular::class);
    }

    public function findTotalesByEstado(string $unidad = 'ALL'): ?array
    {
        $qb = $this->createQueryBuilder('c')
            ->select("SUM(( CASE WHEN( e.estado != 'Baja' )  THEN 1 ELSE 0 END )) AS activos")
            ->addSelect("SUM(( CASE WHEN( e.estado = 'Baja' )  THEN 1 ELSE 0 END )) AS bajas")
            ->leftJoin('c.estado', 'e');

        if($unidad !== 'ALL'){
            $qb->leftJoin('c.usuario', 'u')
                ->leftJoin('u.unidad', 'un')
                ->andWhere('un.nombre = :unidad')
                ->setParameter('unidad', $unidad);
        }

        $totales = $qb->getQuery()
                        ->useQueryCache(true)
                        ->getArrayResult();

        return $totales[0];
    }

    public function findCelularesByEstado(string $estado, string $unidad = 'ALL'): ?array
    {
        $estado = ($estado === 'activos' ? 'Activo' : 'Baja');
        $qb = $this->createQueryBuilder('c')
            ->select('c.id, c.inventario, c.observacion')
            ->addSelect('u.nombre, u.apellidos')
            ->addSelect('un.nombre AS unidad')
            ->addSelect('e.estado AS estado')
            ->addSelect('ma.marca AS marca')
            ->addSelect('m.modelo AS modelo')
            ->addSelect('l.numero')
            ->leftJoin('c.usuario', 'u')
            ->leftJoin('u.unidad', 'un')
            ->leftJoin('c.estado', 'e')
            ->leftJoin('c.modelo', 'm')
            ->leftJoin('m.marca', 'ma')
            ->leftJoin('c.linea', 'l')
        ;

        if('Baja' === $estado){
            $qb->addSelect('c.fechaBaja')
                ->andWhere("e.estado = :estado")
                ->setParameter('estado', $estado);
        }

        if('Baja' !== $estado){
            $qb->andWhere("e.estado != :estado")
                ->setParameter('estado', 'Baja');
        }

        if($unidad !== 'ALL'){
            $qb->andWhere('un.nombre = :unidad')
                ->setParameter('unidad', $unidad);
        }

        return  $qb->getQuery()
                    ->getScalarResult();
    }

    public function findOneCelularById(int $id, string $estado): ?Celular
    {
        $qb = $this->createQueryBuilder('c')
            ->select('c, u, un, e, m, ma, l')
            ->leftJoin('c.usuario', 'u')
            ->leftJoin('u.unidad', 'un')
            ->leftJoin('c.estado', 'e')
            ->leftJoin('c.modelo', 'm')
            ->leftJoin('m.marca', 'ma')
            ->leftJoin('c.linea', 'l')
            ->where('c.id = :id')
            ->setParameter('id', $id);

        if('activo' == $estado){
            $qb->andWhere('e.estado != :estado')
                ->setParameter('estado', 'Baja');
        }

        return $qb->getQuery()
                    ->getOneOrNullResult();
    }

    /** Control de Activos */

    public function findTotalActivosByUnidad(array $unidad): ?int
    {
        $qb = $this->createQueryBuilder('c')
            ->select('COUNT(c.id)')
            ->leftJoin('c.estado', 'e')
            ->leftJoin('c.modelo', 'm')
            ->leftJoin('m.marca', 'ma')
            ->leftJoin('c.usuario', 'u')
            ->leftJoin('u.unidad', 'un')
            ->andWhere("e.estado != :estado")
            ->setParameter('estado', 'Baja');

        $qb->andWhere($qb->expr()->in('un.nombre', $unidad));

        return  $qb->getQuery()
                    ->getSingleScalarResult();
    }

    public function updateFechaControl(int $porciento, array $unidad): void
    {
        $medios = $this->findByFechaControl($porciento, $unidad, true);
        $qb = $this->createQueryBuilder('c');

        $qb->update()
            ->set('c.fechaControl', ':fechaControl')
            ->where($qb->expr()->in('c.id', array_column($medios, 'id')))
            ->setParameter('fechaControl', new \DateTime())
        ;

        $qb->getQuery()->execute();
    }

    public function findByFechaControl(int $porciento, array $unidad, bool $isArray = false): ?array
    {
        $total = round($this->findTotalActivosByUnidad($unidad));
        $control = round(($total*$porciento)/100);
        $qb = $this->createQueryBuilder('c')
            ->select('c, u, un')
            ->join('c.usuario', 'u')
            ->join('u.unidad', 'un')
            ->join('c.estado', 'e');

        $qb->where($qb->expr()->in('un.nombre', $unidad))
            ->andWhere("e.estado != :estado")
            ->setParameter('estado', 'Baja')
            ->orderBy('c.fechaControl', 'ASC')
            ->addOrderBy('c.id', 'ASC');

        if($total > $control){
            $qb->setMaxResults($control);
        }

        if($isArray){
            return $qb->getQuery()->getArrayResult();
        }

        return $qb->getQuery()->getResult();
    }

    public function findByInventario(array $inventario): ?array
    {
        $qb = $this->createQueryBuilder('c');

        $qb->select('c.id, c.inventario')
            ->addSelect('u.nombre, u.apellidos')
            ->addSelect('un.nombre AS unidad')
            ->addSelect('ma.marca AS marca')
            ->addSelect('m.modelo AS modelo')
            ->leftJoin('c.usuario', 'u')
            ->leftJoin('u.unidad', 'un')
            ->leftJoin('c.estado', 'e')
            ->leftJoin('c.modelo', 'm')
            ->leftJoin('m.marca', 'ma')
            ->leftJoin('c.linea', 'l')
            ->where($qb->expr()->in('c.inventario', $inventario))
        ;

        return $qb->getQuery()
                    ->getScalarResult();
    }

    /** Reports **/

    public function findTotalesGroupByUnidad(string $unidad = 'ALL'): ?array
    {
        $qb = $this->createQueryBuilder('c');

        $qb->select('un.nombre AS unidad, COUNT(c.id) AS total')
            ->addSelect("SUM(( CASE WHEN( e.estado = 'Activo' )  THEN 1 ELSE 0 END )) AS activos")
            ->addSelect("SUM(( CASE WHEN( e.estado = 'Baja' )  THEN 1 ELSE 0 END )) AS bajas")
            ->addSelect("SUM(( CASE WHEN( e.estado = 'Roto' )  THEN 1 ELSE 0 END )) AS rotos")
            ->addSelect("SUM(( CASE WHEN( e.estado = 'En taller' )  THEN 1 ELSE 0 END )) AS taller")
            ->addSelect("SUM(( CASE WHEN( e.estado = 'Reserva' )  THEN 1 ELSE 0 END )) AS reserva")
            ->leftJoin('c.usuario', 'u')
            ->leftJoin('c.estado', 'e')
            ->leftJoin('u.unidad', 'un')
            ->groupBy('un.id')
            ->orderBy('un.nombre', 'ASC')
        ;

        if($unidad !== 'ALL'){
            $qb->andWhere('un.nombre = :unidad')
                ->setParameter('unidad', $unidad);
        }

        return $qb->getQuery()
                    ->getArrayResult();
    }
}
