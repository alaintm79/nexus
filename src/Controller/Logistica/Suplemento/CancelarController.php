<?php

namespace App\Controller\Logistica\Suplemento;

use App\Entity\Logistica\Contrato\Estado;
use App\Entity\Logistica\Contrato\Suplemento;
use App\Form\Logistica\Suplemento\CancelarType;
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
class CancelarController extends AbstractController
{
    /**
     * Displays a form to cancel an existing suplemento entity.
     *
     * @Route("/{id<[1-9]\d*>}/cancel",
     *      name="app_suplemento_cancel",
     *      methods={"GET", "POST"})
     */
    public function cancel(Request $request, Suplemento $suplemento): Response
    {
        $contrato = $suplemento->getContrato();
        $form = $this->createForm(CancelarType::class, $suplemento);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $estado = $em->getRepository(Estado::class) ->findOneBy(['estado' => 'CANCELADO']);

            $suplemento->setEstado($estado);

            $this->addFlash('notice', 'Suplemento cancelado con exito!');
            $this->getDoctrine()->getManager()->flush();

            return $this->render('common/notify.html.twig', [
                'redirect' => $this->generateUrl('app_suplemento_index', ['id' => $contrato->getId()])
            ]);
        }

        return $this->render('logistica/suplemento/modal/cancelar_form.html.twig', [
            'form' => $form->createView(),
            'suplemento' => $suplemento,
            'contrato' => $contrato,
        ]);
    }
}
