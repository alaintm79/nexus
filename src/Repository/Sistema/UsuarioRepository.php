<?php

namespace App\Repository\Sistema;

use App\Entity\Sistema\Usuario;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @method Usuario|null find($id, $lockMode = null, $lockVersion = null)
 * @method Usuario|null findOneBy(array $criteria, array $orderBy = null)
 * @method Usuario[]    findAll()
 * @method Usuario[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UsuarioRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Usuario::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(UserInterface $user, string $newEncodedPassword): void
    {
        if (!$user instanceof Usuario) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newEncodedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
    }

    public function findAll (string $unidad = 'ALL', string $estado = 'registrados'): array
    {
        $estado = $estado === 'registrados' ? false : true;
        $qb = $this->createQueryBuilder('u');

        $qb->select("u.id, u.nombre, u.apellidos, u.ci, COALESCE(u.username, '') AS usuario, COALESCE(u.correo, '') AS correo")
            ->addSelect('u.hasAccount, u.isActive, u.isSyncPassword, u.roles')
            ->addSelect("COALESCE(u.observacion, '') AS observacion")
            ->addSelect('un.nombre AS unidad')
            ->addSelect('p.nombre AS plaza')
            ->join('u.unidad', 'un')
            ->leftJoin('u.plaza', 'p')
            ->where($qb->expr()->notIn('u.id', [1]))
            ->andWhere('u.isBaja = :estado')
            ->setParameter('estado', $estado);

        if($unidad !== 'ALL'){
            $qb->andWhere('un.nombre = :unidad')
                ->setParameter('unidad', $unidad);
        }

        return $qb->getQuery()
                    ->getScalarResult();
    }

    public function findByUsuario($usuario): ?Usuario
    {
        return $this->createQueryBuilder('u')
            ->select('u, un, p')
            ->join('u.unidad', 'un')
            ->leftJoin('u.plaza', 'p')
            ->where('u.usuario = :usuario')
            ->andWhere('u.isBaja = false')
            ->andWhere('u.isActive = true')
            ->setParameter('usuario', $usuario)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findNoCuadrosByUnidad ($unidad, $estado): QueryBuilder
    {
        $sqb = $this->_em->createQueryBuilder()
                ->select('t.id')
                ->from('AppBundle\Entity\Cuadros\Cuadro', 'c')
                ->leftJoin('c.trabajador', 't')
                ->where('t.isBaja = false');

        $qb = $this->createQueryBuilder('u');

        $qb->select('u, un')
            ->join('u.unidad', 'un')
            ->where('un.nombre = :unidad')
            ->andWhere('u.isBaja = false')
            ->andWhere($qb->expr()->notIn('u.id', $sqb->getDQL()))
            ->orderBy('u.nombre', 'ASC')
            ->setParameter('unidad', $unidad);

        if($estado === 'jovenes'){
            $qb->andWhere('u.edad < 36');
        }

        return $qb;
    }

    public function findCuadrosByUnidad ($unidad, $estado): QueryBuilder
    {
        $sqb = $this->_em->createQueryBuilder()
                ->select('t.id')
                ->from('AppBundle\Entity\Cuadros\Cuadro', 'c')
                ->leftJoin('c.trabajador', 't')
                ->where('t.isBaja = false');

        $qb = $this->createQueryBuilder('u');

        $qb->select('u, un')
            ->join('u.unidad', 'un')
            ->where('un.nombre = :unidad')
            ->andWhere('u.isBaja = false')
            ->andWhere($qb->expr()->in('u.id', $sqb->getDQL()))
            ->orderBy('u.nombre', 'ASC')
            ->setParameter('unidad', $unidad);

        if($estado === 'jovenes'){
            $qb->andWhere('u.edad < 36');
        }

        return $qb;
    }

    public function findReporteTotalUsuarios(string $unidad = 'ALL'): array
    {
        $qb = $this->createQueryBuilder('u')
            ->select("SUM(( CASE WHEN( u.isBaja = FALSE ) THEN 1 ELSE 0 END )) AS trabajadores")
            ->addSelect("SUM(( CASE WHEN( u.username IS NOT NULL ) THEN 1 ELSE 0 END )) AS usuarios")
            ->addSelect("SUM(( CASE WHEN( u.isActive = TRUE ) THEN 1 ELSE 0 END )) AS usuarios_activos")
            ->addSelect("SUM(( CASE WHEN( u.isActive = FALSE ) THEN 1 ELSE 0 END )) AS usuarios_inactivos");

        if($unidad !== 'ALL'){
            $qb->addSelect('un.nombre AS unidad')
                ->join('u.unidad', 'un')
                ->where('u.isBaja = FALSE')
                ->groupBy('un.nombre')
                ->andWhere('un.nombre = :unidad')
                ->setParameter('unidad', $unidad);
        }

        $reporte = $qb->getQuery()
                    ->useQueryCache(true)
                    ->getScalarResult();

        return $reporte[0];
    }
}
