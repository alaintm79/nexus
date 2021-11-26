<?php

namespace App\Repository\Blog;

use App\Entity\Blog\ViewCumpleanno;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Estado|null find($id, $lockMode = null, $lockVersion = null)
 * @method Estado|null findOneBy(array $criteria, array $orderBy = null)
 * @method Estado[]    findAll()
 * @method Estado[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ViewCumpleannoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ViewCumpleanno::class);
    }

    public function findCumpleannos(): array
    {
        $fecha = new \DateTime('now');
        $dia = $fecha->format('d');
        $mes = $fecha->format('m');

        return $this->createQueryBuilder('c')
            ->select('c.usuario, c.edad, c.dia, c.mes, c.anno, c.unidad')
            ->where('c.mes = :mes')
            ->andWhere('c.dia >= :dia')
            ->orderBy('c.dia', 'ASC')
            ->addOrderBy('c.unidad', 'ASC')
            ->setParameter('dia', $dia)
            ->setParameter('mes', $mes)
            ->getQuery()
            ->getScalarResult();
    }
}
