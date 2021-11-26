<?php

namespace App\Repository\Tic\Linea;

use App\Entity\Tic\Linea\Linea;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\Traits\BootstrapTableTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Linea|null find($id, $lockMode = null, $lockVersion = null)
 * @method Linea|null findOneBy(array $criteria, array $orderBy = null)
 * @method Linea[]    findAll()
 * @method Linea[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LineaRepository extends ServiceEntityRepository
{
    use BootstrapTableTrait;

    private const COLUMNS = [
        'numero' => 'l.numero',
        'pin' => 'l.pin',
        'puk' => 'l.puk',
        'nombre' => 'u.nombre',
        'apellidos' => 'u.apellidos',
        'planVoz' => 'pv.plan',
        'planDatos' => 'pd.plan',
        'isReserva' => 'l.isReserva',
        'fechaBaja' => 'l.fechaBaja',
        'observacion' => 'l.observacion',
        'unidad' => 'un.nombre',
    ];

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Linea::class);
    }

    public function findTotalesByEstado(string $unidad = 'ALL'): ?array
    {
        $qb = $this->createQueryBuilder('l')
            ->select("SUM(( CASE WHEN( l.isBaja = false )  THEN 1 ELSE 0 END )) AS activas")
            ->addSelect("SUM(( CASE WHEN( l.isBaja = true )  THEN 1 ELSE 0 END )) AS bajas");

        if($unidad !== 'ALL'){
            $qb->leftJoin('l.usuario', 'u')
                ->join('u.unidad', 'un')
                ->andWhere('un.nombre = :unidad')
                ->setParameter('unidad', $unidad);
        }

        $totales = $qb->getQuery()
                        ->useQueryCache(true)
                        ->getArrayResult();

        return $totales[0];
    }

    public function findLineasByEstado(array $params, string $estado, string $unidad = 'ALL'): ?array
    {
        $qb = $this->createQueryBuilder('l')
            ->select('l.id, l.numero, l.isReserva, l.observacion, l.isBaja, l.pin, l.puk')
            ->addSelect('u.nombre, u.apellidos')
            ->addSelect('pv.plan AS planVoz')
            ->addSelect('pd.plan AS planDatos')
            ->addSelect('un.nombre AS unidad')
            ->leftJoin('l.usuario', 'u')
            ->leftJoin('l.planVoz', 'pv')
            ->leftJoin('l.planDatos', 'pd')
            ->leftJoin('u.unidad', 'un')
        ;

        if('bajas' === $estado){
            $qb->addSelect('l.fechaBaja');
        }

        if(\array_key_exists('offset', $params)){
            $qb->setFirstResult($params['offset'])
                ->setMaxResults($params['limit']);
        }

        $qb->andWhere('l.isBaja = '.($estado === 'activas' ? 'false' : 'true'));

        if($unidad !== 'ALL'){
            $qb->andWhere('un.nombre = :unidad')
                ->setParameter('unidad', $unidad);
        }

        $this->filter($qb, self::COLUMNS, $params);
        $this->sort($qb, self::COLUMNS, $params);

        return  $qb->getQuery()
                    ->getScalarResult();
    }

    public function findTotalLineasByEstado(array $params, string $estado, string $unidad = 'ALL'): ?int
    {
        $qb = $this->createQueryBuilder('l')
            ->select('COUNT(l.id)')
            ->leftJoin('l.usuario', 'u')
            ->leftJoin('l.planVoz', 'pv')
            ->leftJoin('l.planDatos', 'pd')
            ->leftJoin('u.unidad', 'un')
        ;

        if($unidad !== 'ALL'){
            $qb->andWhere('un.nombre = :unidad')
                ->setParameter('unidad', $unidad);
        }

        $qb->andWhere('l.isBaja = '.($estado === 'activas' ? 'false' : 'true'));

        $this->filter($qb, self::COLUMNS, $params);

        return  $qb->getQuery()
                    ->getSingleScalarResult();
    }

    public function findOneLineaById(int $id)
    {
        return $this->createQueryBuilder('l')
            ->select('l, u, pv, pd, un')
            ->leftJoin('l.usuario', 'u')
            ->leftJoin('l.planVoz', 'pv')
            ->leftJoin('l.planDatos', 'pd')
            ->leftJoin('u.unidad', 'un')
            ->where('l.id = :id')
            ->andWhere('l.isBaja = false')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findLineasByEstadoActivo(): ?array
    {
        return $this->createQueryBuilder('l')
            ->select('l.id, l.numero')
            ->addSelect('u.nombre, u.apellidos')
            ->addSelect('un.nombre AS unidad')
            ->addSelect('p.nombre AS plaza')
            ->leftJoin('l.usuario', 'u')
            ->leftJoin('u.unidad', 'un')
            ->leftJoin('u.plaza', 'p')
            ->andWhere('l.isBaja = false')
            ->andWhere('l.isReserva = false')
            ->orderBy('un.nombre', 'ASC')
            ->addOrderBy('l.numero', 'ASC')
            ->getQuery()
            ->getScalarResult();
    }

    /** Reports **/

    public function findTotalesGroupByUnidad(string $unidad = 'ALL'): ?array
    {
        $qb = $this->createQueryBuilder('l');

        $qb->select('un.nombre AS unidad, COUNT(l.id) AS total')
            ->addSelect('SUM(CASE WHEN l.isReserva = true THEN 1 ELSE 0 END) as reserva')
            ->addSelect("SUM(CASE WHEN p.plan = 'Plan Mensaje + Voz' THEN 1 ELSE 0 END) as sms_voz")
            ->addSelect("SUM(CASE WHEN p.plan != 'Plan Mensaje + Voz' THEN 1 ELSE 0 END) as voz")
            ->addSelect("SUM(CASE WHEN l.planDatos IS NOT NULL THEN 1 ELSE 0 END) as gprs")
            ->join('l.usuario', 'u')
            ->join('u.unidad', 'un')
            ->join('l.planVoz', 'p')
            ->where('l.isBaja = false')
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

    public function findTotalesByPlanVozGroupByUnidad(string $unidad = 'ALL'): ?array
    {
        $qb = $this->createQueryBuilder('l');

        $qb->select('p.plan, COUNT(p.id) AS total, p.cuotaMensual')
            ->addSelect('un.nombre AS unidad')
            ->join('l.usuario', 'u')
            ->join('u.unidad', 'un')
            ->join('l.planVoz', 'p')
            ->where('l.isBaja = false')
            ->groupBy('p.id, un.id')
            ->orderBy('un.nombre', 'ASC')
            ->addOrderBy('total', 'DESC')
        ;

        if($unidad !== 'ALL'){
            $qb->andWhere('un.nombre = :unidad')
                ->setParameter('unidad', $unidad);
        }

        return $qb->getQuery()
                    ->getArrayResult();
    }

    public function findTotalesByPlanDatosGroupByUnidad(string $unidad = 'ALL'): ?array
    {
        $qb = $this->createQueryBuilder('l');

        $qb->select('p.plan, COUNT(p.id) AS total, p.rentaMensual')
            ->addSelect('un.nombre AS unidad')
            ->join('l.usuario', 'u')
            ->join('u.unidad', 'un')
            ->join('l.planDatos', 'p')
            ->where('l.isBaja = false')
            ->groupBy('p.id, un.id')
            ->orderBy('un.nombre', 'ASC')
            ->addOrderBy('total', 'DESC')
        ;

        if($unidad !== 'ALL'){
            $qb->andWhere('un.nombre = :unidad')
                ->setParameter('unidad', $unidad);
        }

        return $qb->getQuery()
                    ->getArrayResult();
    }
}
