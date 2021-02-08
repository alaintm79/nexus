<?php

namespace App\Controller\Logistica\Reportes;

use App\Repository\Logistica\Contrato\ContratoRepository;
use App\Repository\Logistica\Contrato\EjecucionRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Home controller.
 *
 * @Route("logistica/reportes/contratos")
 * @Security("is_granted(['ROLE_JURIDICO', 'ROLE_COMERCIAL', 'ROLE_DIRECTOR'])")
 */
class ContratoController extends AbstractController
{
    /**
     * @Route("/",
     *      name="reportes_contrato_index",
     *      methods={"GET"}
     * )
     */
    public function index (ContratoRepository $contratos)
    {
        return $this->render('logistica/reportes/contrato/index.html.twig', [
            'alertas_avisos' => $contratos->getAlertasAndAvisos(),
        ]);
    }

    /**
     * @Route("/estado",
     *      name="reportes_contrato_estado",
     *      methods={"GET"}
     * )
     */
    public function estado (Request $request, ContratoRepository $contratos)
    {
        $reporte = null;

        if ($request->query->has('reporte')) {
            $reporte = $request->query->get('reporte');
        }

        if(null === $this->reporte($reporte)){
            return $this->redirectToRoute('reportes_contrato_index');
        }

        return $this->render('logistica/reportes/contrato/estado.html.twig', [
            'contratos' => $contratos->findReporteByNombre($reporte),
            'reporte' => $this->reporte($reporte),
        ]);
    }

    /**
     * @Route("/totales",
     *      name="reportes_contrato_total",
     *      methods={"GET"}
     * )
     */
    public function totales (ContratoRepository $contratos)
    {
        return $this->render('logistica/reportes/contrato/total.html.twig', [
            'contratos' => $contratos->findTotalesByEstadoAndUEB(),
            'reporte' => 'Totales por Estado'
        ]);
    }

    /**
     * @Route("/tiempo-tramitacion/",
     *      name="reportes_contrato_tiempo_tramitacion",
     *      methods={"GET"}
     * )
     */
    public function tramitacion (ContratoRepository $contratos)
    {
        return $this->render('logistica/reportes/contrato/tramitacion.html.twig', [
            'tiempos' => $contratos->findTiempoEstimadoByUEB(),
            'reporte' => 'Tiempo Medio de Tramitación'
        ]);
    }

    /**
     * @Route("/ejecucion/",
     *      name="reportes_contrato_ejecucion_index",
     *      methods={"GET|POST"}
     * )
     */
    public function ejecucion(Request $request, EjecucionRepository $ejecuciones)
    {
        $form = $this->createFormBuilder([])
            ->add('start', TextType::class, [
                'label' => 'Fecha Inicial',
            ])
            ->add('end', TextType::class, [
                'label' => 'Fecha Final',
            ])
            ->getForm()
        ;

        $form->handleRequest($request);

        if($form->isSubmitted()){
            $fecha = $form->getData();
            $ejecuciones = $ejecuciones->findEjecucionByRango($fecha['start'], $fecha['end']);
        }

        return $this->render('logistica/reportes/contrato/ejecucion.html.twig', [
            'form' => $form->createView(),
            'ejecuciones' => $ejecuciones
        ]);
    }

    private function reporte($nombre){

        $reportes = [
            'vencidos' => [
                'title' => 'Vencidos'
            ],
            'revision' => [
                'title' => 'En Revisión'
            ],
            'aprobado-menos-45-dias' => [
                'title' => 'Aprobado Menos de 45 Días'
            ],
            'aprobado-mas-45-dias' => [
                'title' => 'Aprobado Más de 45 Días'
            ],
            'aprobado-mas-90-dias' => [
                'title' => 'Aprobado Más de 90 DíaS'
            ],
            'vigencia-menos-30-dias' => [
                'title' => 'Vigencia Menos 30 Días'
            ],
            'vigencia-31-60-dias' => [
                'title' => 'Vigencia de 31 a 60 Días'
            ],
            'vigencia-61-90-dias' => [
                'title' => 'Vigencia de 61 a 90 Días'
            ],
            'sin-ejecucion' => [
                'title' => 'Sin Monto de Ejecución'
            ],
            'sin-ejecucion-cup' => [
                'title' => 'Sin Monto de Ejecución CUP'
            ],
            'sin-ejecucion-cuc' => [
                'title' => 'Sin Monto de Ejecución CUC'
            ]
        ];

        if (array_key_exists($nombre, $reportes)){
            return $reportes[$nombre];
        } else {
            return null;
        }
    }

}
