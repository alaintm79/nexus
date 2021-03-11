<?php

namespace App\Controller\Logistica\Report\Pago;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("logistica/reportes")
 */
class TotalController extends AbstractController
{
    protected $em;

    public function __construct (EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    /**
     * @Route("/pagos/total",
     *      name="report_logistica_pago_total",
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

        return $this->render('logistica/report/pago/total.html.twig', [
            'pagos' => isset($query) ? $query : [],
            'form' => $form->createView(),
        ]);
    }

    /*
    *   Report Form
    */

    private function form(): ?object
    {
        return $this->createFormBuilder([])
            ->add('start', TextType::class, [
                'label' => 'Fecha Inicial',
            ])
            ->add('end', TextType::class, [
                'label' => 'Fecha Final',
            ])
            ->getForm();
    }

    /*
    *   Report Query
    */

    private function query($start, $end): ?array
    {
        $qb = $this->em->createQueryBuilder('s');

        return $qb->select('u.nombre AS unidad')
            ->addSelect("SUM(( CASE WHEN( t.tipo = 'PAGO POSTERIOR' ) THEN s.importeTotal ELSE 0 END )) AS pago_posterior")
            ->addSelect("SUM(( CASE WHEN( t.tipo = 'PAGO ANTICIPADO' ) THEN s.importeTotal ELSE 0 END )) AS pago_anticipado")
            ->addSelect("SUM(( CASE WHEN( t.tipo = 'OPERACIONES' ) THEN s.importeTotal ELSE 0 END )) AS pago_operaciones")
            ->addSelect("SUM(( CASE WHEN( t.tipo = 'INVERSIONES' ) THEN s.importeTotal ELSE 0 END )) AS pago_inversiones")
            ->from('App:Logistica\Pago\Solicitud', 's')
            ->leftJoin('s.estado', 'e')
            ->leftJoin('s.tipoPago', 't')
            ->leftJoin('s.contrato', 'c')
            ->leftJoin('c.procedencia', 'u')
            ->where('e.estado = :estado')
            ->andWhere($qb->expr()->between('s.fechaModificacion', ':inicio', ':fin'))
            ->setParameter('estado', 'PAGADO')
            ->setParameter('inicio', $start)
            ->setParameter('fin', $end)
            ->groupBy('u.nombre')
            ->orderBy('u.nombre')
            ->getQuery()
            ->useQueryCache(true)
            ->getScalarResult();
    }
}
