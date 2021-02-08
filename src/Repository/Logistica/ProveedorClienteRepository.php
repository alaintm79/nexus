<?php

namespace App\Repository\Logistica;

use App\Entity\Logistica\ProveedorCliente;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ProveedorCliente|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProveedorCliente|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProveedorCliente[]    findAll()
 * @method ProveedorCliente[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProveedorClienteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProveedorCliente::class);
    }

    public function findAll(): array
    {
        return $this->createQueryBuilder('pc')
            ->select('pc.id, pc.nombre, pc.isCliente, pc.isProveedor, pc.isModificable, pc.observacion, pc.fechaModificacion')
            ->orderBy('pc.nombre', 'ASC')
            ->getQuery()
            ->getScalarResult();
    }

    public function findById(int $id): ?ProveedorCliente
    {
        return $this->createQueryBuilder('pc')
            ->where('pc.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getSingleResult();
    }

    public function findByTipo(string $tipo)
    {
        $qb = $this->createQueryBuilder('pc')
                ->orderBy('pc.nombre', 'ASC');

        if('proveedor' === $tipo){
            return $qb->where('pc.isProveedor = true');
        }

        if('cliente' === $tipo){
            return $qb->where('pc.isCliente = true');
        }
    }
}
