<?php

namespace App\Controller\Logistica\Contrato;

use App\Controller\Logistica\Traits\VigenciaTrait;
use App\Entity\Logistica\Contrato\Estado;
use App\Form\Logistica\Contrato\FirmaType;
use App\Entity\Logistica\Contrato\Contrato;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Contrato controller.
 *
 * @Route("logistica/contrato")
 *
 */
class FirmaController extends AbstractController
{
    use VigenciaTrait;
    /**
     * Displays a form to approve contrato entity.
     *
     * @Route("/{id<[1-9]\d*>}/firm",
     *      name="app_contrato_firm",
     *      methods={"GET", "POST"}
     * )
     */
    public function firm(Request $request, Contrato $contrato): Response
    {
        $form = $this->createForm(FirmaType::class, $contrato);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $estado = $em->getRepository(Estado::class)->findOneBy(['estado' => 'FIRMADO']);
            $vigencia = $contrato->getVigencia()->getVigencia();

            if(!is_null($contrato->getValorCup())){
                $contrato->setValorEjecucionCup($contrato->getValorCup());
                $contrato->setValorTotalCup($contrato->getValorCup());
            }

            if(!in_array($vigencia, ['CUMPLIMIENTO OBLIGACIONES', 'CUMPLIMIENTO FECHA', 'PERMANENTE']))
            {
                $firma = $contrato->getFechaFirma()->format('Y-m-d');
                $fechaVigencia = new \DateTime($firma);

                $contrato->setFechaVigencia($fechaVigencia->modify($this->vigenciaFormat($vigencia)));
            }

            $contrato->setEstado($estado);
            $contrato->setIsModificable(false);

            $this->addFlash('notice', 'Contrato firmado con exito!');

            $em->flush();

            return $this->render('common/notify.html.twig', []);
        }

        return $this->render('logistica/contrato/modal/firma_form.html.twig', [
            'form' => $form->createView(),
            'contrato' => $contrato,
        ]);
    }
}
