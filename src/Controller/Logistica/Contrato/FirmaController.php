<?php

namespace App\Controller\Logistica\Contrato;

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

                $contrato->setFechaVigencia($fechaVigencia->modify($this->vigencia($vigencia)));
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

    private function vigencia(string $vigencia): ?string
    {
        switch($vigencia){
            case '6 MESES':
                $vigencia = '+6 month';
                break;
            case '1 AÑO':
                $vigencia = '+1 years';
                break;
            case '2 AÑOS':
                $vigencia = '+2 years';
                break;
            case '3 AÑOS':
                $vigencia = '+3 years';
                break;
            case '4 AÑOS':
                $vigencia = '+4 years';
                break;
            case '5 AÑOS':
                $vigencia = '+5 years';
                break;
            default:
                return null;
        }

        return $vigencia;
    }
}
