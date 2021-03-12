<?php

namespace App\Controller\Logistica\Suplemento;

use App\Controller\Logistica\Traits\VigenciaTrait;
use App\Entity\Logistica\Contrato\Contrato;
use App\Entity\Logistica\Contrato\Estado;
use App\Entity\Logistica\Contrato\Suplemento;
use App\Form\Logistica\Suplemento\FirmaType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Suplemento controller.
 *
 * @Route("logistica/suplemento")
 *
 */
class FirmaController extends AbstractController
{
    use VigenciaTrait;
    /**
     * Displays a form to firm an existing suplemento entity.
     *
     * @Route("/{id<[1-9]\d*>}/firm",
     *      name="app_suplemento_firm",
     *      methods={"GET", "POST"})
     */
    public function firm(Request $request, Suplemento $suplemento): Response
    {
        $contrato = $suplemento->getContrato();
        $form = $this->createForm(FirmaType::class, $suplemento);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $estado = $em->getRepository(Estado::class)->findOneBy(['estado' => 'FIRMADO']);

            if(!is_null($suplemento->getValorCup())){
                $contrato->setValorEjecucionCup($contrato->getValorEjecucionCup() + $suplemento->getValorCup());
                $contrato->setValorTotalCup($contrato->getValorTotalCup() + $suplemento->getValorCup());
            }

            if(!in_array($suplemento->getVigencia(), ['CUMPLIMIENTO OBLIGACIONES', 'CUMPLIMIENTO FECHA', 'PERMANENTE', null])){
                $vigencia = $suplemento->getVigencia();
                $fechaVigencia = new \DateTime($suplemento->getFechaFirma()->format('Y-m-d'));

                $suplemento->setFechaVigencia($fechaVigencia->modify($this->vigenciaFormat($vigencia)));
                $contrato->setFechaVigencia($suplemento->getFechaVigencia());
            }

            $suplemento->setEstado($estado);

            $this->addFlash('notice', 'Suplemento firmado con exito!');
            $this->getDoctrine()->getManager()->flush();

            return $this->render('common/notify.html.twig', [
                'redirect' => $this->generateUrl('app_suplemento_index', ['id' => $contrato->getId()])
            ]);
        }

        return $this->render('logistica/suplemento/modal/firma_form.html.twig', [
            'form' => $form->createView(),
            'suplemento' => $suplemento,
            'contrato' => $contrato,
        ]);
    }
}
