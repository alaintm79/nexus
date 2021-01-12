<?php

namespace App\Repository\Sistema;

use App\Entity\Sistema\Rol;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Rol|null find($id, $lockMode = null, $lockVersion = null)
 * @method Rol|null findOneBy(array $criteria, array $orderBy = null)
 * @method Rol[]    findAll()
 * @method Rol[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RolRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Rol::class);
    }

    public function findRoles(): array
    {
        $qb = $this->createQueryBuilder('r');
        $result = $qb->select('r.rol, r.descripcion, g.grupo')
            ->join('r.grupo', 'g')
            ->orderBy('g.id', 'ASC')
            ->getQuery()
            ->getArrayResult();

        $roles = [];

         foreach($result as $rol){
             $roles[$rol['grupo']][$rol['descripcion']] = $rol['rol'];
        }

        return $roles;
    }
}
