<?php

namespace App\Controller\Logistica\Report\Pago;

use App\Entity\Logistica\Pago\Estado;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("logistica/reportes")
 */
class PeriodoController extends AbstractController
{
    protected $em;

    public function __construct (EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    /**
     * @Route("/pagos/periodo",
     *      name="report_logistica_pago_periodo",
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
            $estado = $form->get('estado')->getData();
            $query = $this->query($start, $end, $estado);
        }

        return $this->render('logistica/report/pago/periodo.html.twig', [
            'solicitudes' => isset($query) ? $query : [],
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
            ->add('estado', EntityType::class, [
                'class' => Estado::class,
                'required' => false
            ])
            ->getForm();
    }

    /*
    *   Report Query
    */

    private function query($start, $end, ?string $estado = ''): ?array
    {
        $qb = $this->em->createQueryBuilder('s');

        return $qb->select('s.id, s.noDocumentoPrimario, s.fechaDocumento, s.fechaSolicitud')
                    ->addSelect('s.importeCup, s.importeCuc, s.importeTotal')
                    ->addSelect('c.numero AS contratoNumero')
                    ->addSelect('e.estado')
                    ->addSelect('u.nombre AS unidad')
                    ->from('App:Logistica\Pago\Solicitud', 's')
                    ->leftJoin('s.contrato', 'c')
                    ->leftJoin('s.estado', 'e')
                    ->leftJoin('c.procedencia', 'u')
                    ->where($qb->expr()->like('e.estado', ':estado'))
                    ->andWhere($qb->expr()->between('s.fechaSolicitud', ':inicio', ':fin'))
                    ->setParameter('estado', '%'.$estado.'%')
                    ->setParameter('inicio', $start)
                    ->setParameter('fin', $end)
                    ->getQuery()
                    ->useQueryCache(true)
                    ->getScalarResult();
    }
}
