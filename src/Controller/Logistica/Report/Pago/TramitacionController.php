<?php

namespace App\Controller\Logistica\Report\Pago;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("logistica/reportes")
 */
class TramitacionController extends AbstractController
{
    protected $em;

    public function __construct (EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    /**
     * @Route("/pagos/tramitacion",
     *      name="report_logistica_pago_tramitacion",
     *      methods={"GET", "POST"}
     * )
     */
    public function index(Request $request): Response
    {
        return $this->render('logistica/report/pago/tramitacion.html.twig', [
            'tiempos' => $this->query(),
        ]);
    }

    /*
    *   Report Query
    */

    private function query(): ?array
    {
        return $this->em->createQueryBuilder('s')
            ->select('u.nombre AS unidad')
            ->addSelect("AVG(DATE_DIFF(s.fechaModificacion, s.fechaSolicitud)) AS estimado")
            ->from('App:Logistica\Pago\Solicitud', 's')
            ->leftJoin('s.estado', 'e')
            ->leftJoin('s.contrato', 'c')
            ->leftJoin('c.procedencia', 'u')
            ->where('e.estado = :estado')
            ->setParameter('estado', 'PAGADO')
            ->groupBy('u.nombre')
            ->orderBy('u.nombre')
            ->getQuery()
            ->useQueryCache(true)
            ->getScalarResult();
    }
}
