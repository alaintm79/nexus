<?php

namespace App\Repository\Logistica\Contrato;

use App\Entity\Logistica\Contrato\Contrato;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Contrato|null find($id, $lockMode = null, $lockVersion = null)
 * @method Contrato|null findOneBy(array $criteria, array $orderBy = null)
 * @method Contrato[]    findAll()
 * @method Contrato[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContratoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Contrato::class);
    }

    public function findById(int $id): ?Contrato
    {
        return $this->createQueryBuilder('c')
            ->select('c, cat, p, e, v, pc')
            ->leftJoin('c.categoria', 'cat')
            ->leftJoin('c.procedencia', 'p')
            ->leftJoin('c.estado', 'e')
            ->leftJoin('c.vigencia', 'v')
            ->leftJoin('c.proveedorCliente', 'pc')
            ->where('c.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findEditById(int $id): ?Contrato
    {
        return $this->createQueryBuilder('c')
            ->where('c.id = :id')
            ->andWhere('c.isModificable = true')
            ->setParameter('id', $id)
            ->getQuery()
            ->getSingleResult();
    }

    public function findContratoFirmadoById(int $id): ?Contrato
    {
        return $this->createQueryBuilder('c')
            ->where('c.id = :id')
            ->andWhere('c.isModificable = false')
            ->setParameter('id', $id)
            ->getQuery()
            ->getSingleResult();
    }

    public function findContratosByTipo(string $tipo): array
    {
        $qb = $this->createQueryBuilder('c');

        return $qb->select('c.numero, c.id, cat.categoria AS categoria, p.nombre AS procedencia, c.tipo')
            ->addSelect('cs.nombre AS proveedorCliente, e.estado, v.vigencia, c.fechaVigencia')
            ->addSelect("(DATE_DIFF(c.fechaVigencia, CURRENT_DATE())) AS diasVigencia")
            ->addSelect('c.isModificable')
            ->addSelect('c.fechaModificacion')
            ->addSelect('c.observacion')
            ->join('c.proveedorCliente', 'cs')
            ->leftJoin('c.categoria', 'cat')
            ->leftJoin('c.procedencia', 'p')
            ->leftJoin('c.estado', 'e')
            ->leftJoin('c.vigencia', 'v')
            ->where('c.tipo = :tipo')
            ->andWhere($qb->expr()->in('e.estado', ['REVISION', 'APROBADO', 'FIRMADO', 'NO APROBADO']))
            ->orderBy('diasVigencia', 'DESC')
            ->setParameter('tipo', $tipo === 'proveedor' ? 'p' : 'c')
            ->getQuery()
            ->getScalarResult();
    }

    public function findContratosCancelados(): array
    {
        $qb = $this->createQueryBuilder('c');

        return $qb->select('c.id, c.numero, c.tipo, cat.categoria AS categoria, p.nombre AS procedencia')
            ->addSelect('cs.nombre AS proveedorCliente, v.vigencia AS vigencia, c.fechaCancelado')
            ->addSelect('c.isModificable, c.fechaModificacion, c.observacion')
            ->join('c.proveedorCliente', 'cs')
            ->leftJoin('c.categoria', 'cat')
            ->leftJoin('c.procedencia', 'p')
            ->leftJoin('c.estado', 'e')
            ->leftJoin('c.vigencia', 'v')
            ->Where($qb->expr()->in('e.estado', ['CANCELADO']))
            ->getQuery()
            ->getScalarResult();
    }

    public function findContratosNoAprobados(): array
    {
        $qb = $this->createQueryBuilder('c');

        return $qb->select('c.numero, c.uuid, c.tipo, cat.categoria AS categoria, p.nombre AS procedencia')
            ->addSelect('cs.nombre AS proveedorCliente, v.vigencia AS vigencia')
            ->addSelect('c.isModificable')
            ->join('c.proveedorCliente', 'cs')
            ->leftJoin('c.categoria', 'cat')
            ->leftJoin('c.procedencia', 'p')
            ->leftJoin('c.estado', 'e')
            ->leftJoin('c.vigencia', 'v')
            ->Where($qb->expr()->in('e.estado', ['NO APROBADO']))
            ->getQuery()
            ->getScalarResult();
    }

    public function findContratosByEstado(string $estado): array
    {
        $estado = \str_replace('-', ' ', $estado);
        $qb = $this->createQueryBuilder('c');

        return $qb->select('c.id, c.numero, c.tipo, cat.categoria AS categoria, p.nombre AS procedencia')
            ->addSelect('cs.nombre AS proveedorCliente, v.vigencia AS vigencia')
            ->addSelect('c.isModificable, c.fechaModificacion, c.observacion')
            ->join('c.proveedorCliente', 'cs')
            ->leftJoin('c.categoria', 'cat')
            ->leftJoin('c.procedencia', 'p')
            ->leftJoin('c.estado', 'e')
            ->leftJoin('c.vigencia', 'v')
            ->where($qb->expr()->in('e.estado', ':estado'))
            ->setParameter('estado', \strtoupper($estado))
            ->getQuery()
            ->getScalarResult();
    }

    public function findContratosSinVigencia(): array
    {
        $qb = $this->createQueryBuilder('c');

        return $qb->select('c.id, c.numero, c.tipo, cat.categoria AS categoria, p.nombre AS procedencia')
            ->addSelect('cs.nombre AS proveedorCliente, v.vigencia AS vigencia')
            ->addSelect('c.isModificable, c.fechaModificacion, c.observacion')
            ->join('c.proveedorCliente', 'cs')
            ->leftJoin('c.categoria', 'cat')
            ->leftJoin('c.procedencia', 'p')
            ->leftJoin('c.estado', 'e')
            ->leftJoin('c.vigencia', 'v')
            ->where($qb->expr()->in('e.estado', ['SIN VIGENCIA']))
            ->getQuery()
            ->getScalarResult();
    }

    public function findContratosVencidos(): array
    {
        $qb = $this->createQueryBuilder('c');

        return $qb->select('c')
            ->leftJoin('c.estado', 'e')
            ->where($qb->expr()->in('e.estado', ['FIRMADO']))
            ->andWhere('c.fechaVigencia IS NOT NULL')
            ->andWhere('DATE_DIFF(c.fechaVigencia, CURRENT_DATE()) < 0')
            ->getQuery()
            ->getScalarResult();
    }

    public function findUltimoContrato(string $tipo): ?Contrato
    {
        $date = new \DateTime('now');
        $qb = $this->createQueryBuilder('c');

        return $qb->select('c')
            ->where('c.tipo = :tipo')
            ->andWhere($qb->expr()->like('c.numero', ':numero'))
            ->setParameter('numero', '%-'.$date->format('Y'))
            ->setParameter('tipo', $tipo)
            ->orderBy('c.numero', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function getArrayContratosByProveedores(): ?Query
    {

        $qb = $this->createQueryBuilder('c');

        return $qb->select('c')
            ->join('c.proveedorCliente', 'cs')
            ->leftJoin('c.categoria', 'cat')
            ->leftJoin('c.procedencia', 'p')
            ->leftJoin('c.estado', 'e')
            ->leftJoin('c.vigencia', 'v')
            ->where('c.tipo = :tipo')
            ->andWhere($qb->expr()->in('e.estado', ['FIRMADO']))
            ->andWhere('c.valorCuc IS NOT NULL OR c.valorCup IS NOT NULL')
            ->setParameter('tipo', 'p')
            ->getQuery();
    }

    public function findTotalByEstado(): array
    {

        $reporte = $this->createQueryBuilder('c')
            ->select("SUM(( CASE WHEN( e.estado = 'FIRMADO' )  THEN 1 ELSE 0 END )) AS total")
            ->addSelect("SUM(( CASE WHEN( e.estado = 'APROBADO' )  THEN 1 ELSE 0 END )) AS aprobados")
            ->addSelect("SUM(( CASE WHEN( e.estado = 'REVISION' )  THEN 1 ELSE 0 END )) AS revision")
            ->addSelect("SUM(( CASE WHEN( e.estado = 'CANCELADO' )  THEN 1 ELSE 0 END )) AS cancelados")
            ->addSelect("SUM(( CASE WHEN( DATE_DIFF(c.fechaVigencia, CURRENT_DATE()) <= 0 )  THEN 1 ELSE 0 END )) AS vencidos")
            ->leftJoin('c.estado', 'e')
            ->getQuery()
            ->useQueryCache(true)
            ->getArrayResult();

        return $reporte[0];
    }

    public function findReporteByNombre(string $nombre): array
    {

        $qb = $this->createQueryBuilder('c');

        $qb->select('c.id, c.numero, c.tipo, cat.categoria AS categoria, p.nombre AS procedencia')
            ->addSelect('cs.nombre AS proveedor, v.vigencia AS vigencia, e.estado AS estado')
            ->addSelect('c.fechaVigencia, c.fechaFirma, c.fechaAprobado')
            ->addSelect('(DATE_DIFF(c.fechaVigencia, CURRENT_DATE())) AS diasVigencia')
            ->addSelect('c.observacion')
            ->join('c.proveedorCliente', 'cs')
            ->leftJoin('c.categoria', 'cat')
            ->leftJoin('c.procedencia', 'p')
            ->leftJoin('c.estado', 'e')
            ->leftJoin('c.vigencia', 'v')
        ;

        /* Vencidos */

        if($nombre === 'vigencia-61-90-dias'){
            $qb->where('DATE_DIFF(c.fechaVigencia, CURRENT_DATE()) >= 61 AND DATE_DIFF(c.fechaVigencia, CURRENT_DATE()) <= 90');
        }

        if($nombre === 'vigencia-31-60-dias'){
            $qb->where('DATE_DIFF(c.fechaVigencia, CURRENT_DATE()) >= 31 AND DATE_DIFF(c.fechaVigencia, CURRENT_DATE()) <= 60');
        }

        if($nombre === 'vigencia-menos-30-dias'){
            $qb->where('DATE_DIFF(c.fechaVigencia, CURRENT_DATE()) >= 1 AND DATE_DIFF(c.fechaVigencia, CURRENT_DATE()) <= 30');
        }

        if($nombre === 'vencidos'){
            $qb->where('DATE_DIFF(c.fechaVigencia, CURRENT_DATE()) <= 0');
        }

        /* Revision */
        if($nombre === 'revision'){
            $qb->where('e.estado = :estado')
                ->setParameter('estado', 'REVISION');
        }

        /* Aprobados */

        if($nombre === 'aprobado-menos-45-dias'){
            $qb->where("DATE_DIFF(CURRENT_DATE(), c.fechaAprobado ) < 45 AND e.estado = 'APROBADO'");
        }

        if($nombre === 'aprobado-mas-45-dias'){
            $qb->where("DATE_DIFF(CURRENT_DATE(), c.fechaAprobado ) > 45 AND DATE_DIFF(CURRENT_DATE(), c.fechaAprobado ) < 90 AND e.estado = 'APROBADO'");
        }

        if($nombre === 'aprobado-mas-90-dias'){
            $qb->where("DATE_DIFF(CURRENT_DATE(), c.fechaAprobado ) > 90 AND e.estado = 'APROBADO'");
        }

        /* Liquidez */

        if($nombre === 'sin-ejecucion'){
            $qb->where("c.valorEjecucionCup = 0 AND c.valorEjecucionCuc = 0 AND e.estado = 'FIRMADO'");
        }

        return $qb->getQuery()
            ->getResult();
    }

    /* Menu */
    public function findContratosVigenteProveedores()
    {
        return $this->createQueryBuilder('c')
            ->select('c, cs')
            ->join('c.proveedorCliente', 'cs')
            ->leftJoin('c.procedencia', 'p')
            ->leftJoin('c.estado', 'e')
            ->where('c.tipo = :tipo')
            ->andWhere('e.estado = :estado')
            ->orderBy('cs.nombre', 'ASC')
            ->addOrderBy('c.numero', 'ASC')
            ->setParameter('tipo', 'p')
            ->setParameter('estado', 'FIRMADO');
    }
}
