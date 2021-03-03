<?php

namespace App\Controller\Logistica\Reports\Contrato;

use App\Repository\Logistica\Contrato\EjecucionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Home controller.
 *
 * @Route("logistica/reporte/contratos")
 */
class EjecucionController extends AbstractController
{
    /**
     * @Route("/ejecucion/",
     *      name="app_reports_logistica_contrato_ejecucion",
     *      methods={"GET", "POST"}
     * )
     */
    public function index(Request $request, EjecucionRepository $ejecuciones): Response
    {
        $form = $this->formRange();

        $form->handleRequest($request);

        if($form->isSubmitted()){
            $start = $form->get('start')->getData();
            $end = $form->get('end')->getData();
            $ejecuciones = $ejecuciones->findEjecucionByRango($start, $end);
        }

        return $this->render('logistica/reports/contrato/ejecucion.html.twig', [
            'ejecuciones' => $ejecuciones,
            'form' => $form->createView()
        ]);
    }

    private function formRange(): ?object
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
}
