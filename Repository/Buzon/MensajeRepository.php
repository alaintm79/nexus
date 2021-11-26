<?php

namespace App\Repository\Buzon;

use App\Entity\Buzon\Mensaje;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\Traits\BootstrapTableTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Mensaje|null find($id, $lockMode = null, $lockVersion = null)
 * @method Mensaje|null findOneBy(array $criteria, array $orderBy = null)
 * @method Mensaje[]    findAll()
 * @method Mensaje[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MensajeRepository extends ServiceEntityRepository
{
    use BootstrapTableTrait;

    private const COLUMNS = [
        'mensaje' => 'm.mensaje',
        'fechaCreado' => 'm.fechaCreado',
        'tipoMensaje' => 't.tipoMensaje',
        'nombre' => 'u.nombre',
        'apellidos' => 'u.apellidos',
        'unidad' => 'un.nombre',
    ];

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Mensaje::class);
    }

    public function findMensajes(array $params): ?array
    {
        $qb = $this->createQueryBuilder('m')
            ->select('m.id, m.mensaje, m.fechaCreado')
            ->addSelect('t.tipoMensaje')
            ->addSelect('u.nombre, u.apellidos')
            ->addSelect('un.nombre AS unidad')
            ->leftJoin('m.tipoMensaje', 't')
            ->leftJoin('m.usuario', 'u')
            ->leftJoin('u.unidad', 'un')
            ->setFirstResult($params['offset'])
            ->setMaxResults($params['limit'])
        ;

        $this->search($qb, self::COLUMNS, $params);
        $this->sort($qb, self::COLUMNS, $params);

        return  $qb->getQuery()
                    ->getScalarResult();
    }


    public function findTotalMensajes(array $params): ?int
    {
        $qb = $this->createQueryBuilder('m')
            ->select('COUNT(m.id)')
        ;

        $this->search($qb, self::COLUMNS, $params);

        return  $qb->getQuery()
                    ->getSingleScalarResult();
    }

    public function findById(int $id): ?Mensaje
    {
        $qb = $this->createQueryBuilder('m')
            ->select('m, t, u, un')
            ->leftJoin('m.tipoMensaje', 't')
            ->leftJoin('m.usuario', 'u')
            ->leftJoin('u.unidad', 'un')
            ->where('m.id = :id')
            ->setParameter('id', $id)
        ;

        return  $qb->getQuery()
                    ->getOneOrNullResult();
    }
}
