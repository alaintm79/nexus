<?php

namespace App\Repository\Contacto;

use App\Entity\Contacto\Contacto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Contacto|null find($id, $lockMode = null, $lockVersion = null)
 * @method Contacto|null findOneBy(array $criteria, array $orderBy = null)
 * @method Contacto[]    findAll()
 * @method Contacto[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContactoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Contacto::class);
    }

    public function findAll(): array
    {
        return $this->createQueryBuilder('c')
            ->select("c.id, c.nombre, c.apellidos, COALESCE(c.telefonoMovil, '') AS telefonoMovil")
            ->addSelect("COALESCE(c.telefonoFijo, '') AS telefonoFijo, COALESCE(c.telefonoFijoTrabajo, '') AS telefonoFijoTrabajo")
            ->addSelect("COALESCE(c.extension, '') AS extension, COALESCE(c.correo1, '') AS correo1, COALESCE(c.correo2, '') AS correo2")
            ->addSelect("COALESCE(c.cargo, '') AS cargo, COALESCE(u.nombre, '') AS ubicacion, COALESCE(c.observacion, '') AS observacion")
            ->leftJoin('c.ubicacion', 'u')
            ->getQuery()
            ->getScalarResult();
    }

    public function findReporteTotal(): int
    {
        return $this->createQueryBuilder('c')
            ->select('count(c.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }
}
