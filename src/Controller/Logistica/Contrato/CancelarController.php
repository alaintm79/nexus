<?php

namespace App\Controller\Logistica\Contrato;

use App\Entity\Logistica\Contrato\Estado;
use App\Entity\Logistica\Contrato\Contrato;
use App\Form\Logistica\Contrato\CancelarType;
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
class CancelarController extends AbstractController
{
    /**
     * Displays a form to cancel contrato entity.
     *
     * @Route("/{id<[1-9]\d*>}/cancel",
     *      name="app_contrato_cancel",
     *      methods={"GET", "POST"}
     * )
     */
    public function cancel(Request $request, Contrato $contrato): Response
    {
        $form = $this->createForm(CancelarType::class, $contrato);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $estado = $em->getRepository(Estado::class) ->findOneBy(['estado' => 'CANCELADO']);

            $contrato->setEstado($estado);
            $contrato->setIsModificable(false);

            $this->addFlash('notice', 'Contrato cancelado con exito!');

            $em->flush();

            return $this->render('common/notify.html.twig', []);
        }

        return $this->render('logistica/contrato/modal/cancelar_form.html.twig', [
            'form' => $form->createView(),
            'contrato' => $contrato,
        ]);
    }
}
