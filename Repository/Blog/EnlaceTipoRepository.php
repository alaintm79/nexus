<?php

namespace App\Repository\Blog;

use App\Entity\Blog\EnlaceTipo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method EnlaceTipo|null find($id, $lockMode = null, $lockVersion = null)
 * @method EnlaceTipo|null findOneBy(array $criteria, array $orderBy = null)
 * @method EnlaceTipo[]    findAll()
 * @method EnlaceTipo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EnlaceTipoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EnlaceTipo::class);
    }
}
