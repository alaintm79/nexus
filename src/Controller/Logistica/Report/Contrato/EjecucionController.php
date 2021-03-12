<?php

namespace App\Controller\Logistica\Report\Contrato;

use App\Controller\Logistica\Report\Traits\FormRangeTrait;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 *
 * @Route("logistica/reportes")
 */
class EjecucionController extends AbstractController
{
    use FormRangeTrait;

    protected $em;

    public function __construct (EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/contratos/ejecucion/",
     *      name="report_logistica_contrato_ejecucion",
     *      methods={"GET", "POST"}
     * )
     */
    public function index(Request $request): Response
    {
        $form = $this->form();

        $form->handleRequest($request);

        if($form->isSubmitted()){
            $start = $form->get('start')->getData();
            $end = $form->get('end')->getData();
            $query = $this->query($start, $end);
        }

        return $this->render('logistica/report/contrato/ejecucion.html.twig', [
            'ejecuciones' => isset($query) ? $query : [],
            'form' => $form->createView()
        ]);
    }

    /*
    *   Report Query
    */

    private function query($start, $end): ?array
    {
        $qb = $this->em->createQueryBuilder('e');

        return $qb->select('e.saldoCup, e.saldoCuc,e.fechaModificacion')
                    ->addSelect('s.noDocumentoPrimario, s.noDocumentoSecundario')
                    ->addSelect('s.importeCup, s.importeCuc')
                    ->addSelect('d.tipo')
                    ->addSelect('c.id, c.numero')
                    ->addSelect('u.nombre AS unidad')
                    ->addSelect('pc.nombre AS proveedorCliente')
                    ->from('App:Logistica\Contrato\Ejecucion', 'e')
                    ->leftJoin('e.solicitud', 's')
                    ->leftJoin('s.tipoDocumento', 'd')
                    ->leftJoin('s.contrato', 'c')
                    ->leftJoin('c.proveedorCliente', 'pc')
                    ->leftJoin('c.procedencia', 'u')
                    ->where($qb->expr()->between('e.fechaModificacion', ':inicio', ':fin'))
                    ->setParameter('inicio', $start)
                    ->setParameter('fin', $end)
                    ->getQuery()
                    ->useQueryCache(true)
                    ->getScalarResult();
    }
}
