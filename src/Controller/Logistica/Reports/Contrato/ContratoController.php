<?php

namespace App\Controller\Logistica\Reports\Contrato;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Home controller.
 *
 * @Route("logistica/reporte/contratos")
 * @Security("is_granted(['ROLE_JURIDICO', 'ROLE_COMERCIAL', 'ROLE_DIRECTOR'])")
 */
class ContratoController extends AbstractController
{
    /**
     * @Route("/estado",
     *      name="reporte_contrato_estado",
     *      methods={"GET"}
     * )
     */
    // public function estado (Request $request, ContratoRepository $contratos): Response
    // {
    //     $reporte = null;

    //     if ($request->query->has('reporte')) {
    //         $reporte = $request->query->get('reporte');
    //     }

    //     if(null === $this->reporte($reporte)){
    //         return $this->redirectToRoute('reporte_contrato_index');
    //     }

    //     return $this->render('logistica/reporte/contrato/estado.html.twig', [
    //         'contratos' => $contratos->findReporteByNombre($reporte),
    //         'reporte' => $this->reporte($reporte),
    //     ]);
    // }

    // private function reporte($nombre): ?string
    // {
    //     $reportes = [
    //         'vencidos' => [
    //             'title' => 'Vencidos'
    //         ],
    //         'revision' => [
    //             'title' => 'En Revisión'
    //         ],
    //         'aprobado-menos-45-dias' => [
    //             'title' => 'Aprobado Menos de 45 Días'
    //         ],
    //         'aprobado-mas-45-dias' => [
    //             'title' => 'Aprobado Más de 45 Días'
    //         ],
    //         'aprobado-mas-90-dias' => [
    //             'title' => 'Aprobado Más de 90 DíaS'
    //         ],
    //         'vigencia-menos-30-dias' => [
    //             'title' => 'Vigencia Menos 30 Días'
    //         ],
    //         'vigencia-31-60-dias' => [
    //             'title' => 'Vigencia de 31 a 60 Días'
    //         ],
    //         'vigencia-61-90-dias' => [
    //             'title' => 'Vigencia de 61 a 90 Días'
    //         ],
    //         'sin-ejecucion' => [
    //             'title' => 'Sin Monto de Ejecución'
    //         ],
    //         'sin-ejecucion-cup' => [
    //             'title' => 'Sin Monto de Ejecución CUP'
    //         ],
    //         'sin-ejecucion-cuc' => [
    //             'title' => 'Sin Monto de Ejecución CUC'
    //         ]
    //     ];

    //     if (array_key_exists($nombre, $reportes)){
    //         return $reportes[$nombre];
    //     } else {
    //         return null;
    //     }
    // }

}
