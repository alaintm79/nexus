<?php

namespace App\Controller\Logistica\Suplemento;

use App\Entity\Logistica\Contrato\Estado;
use App\Entity\Logistica\Contrato\Suplemento;
use App\Form\Logistica\Suplemento\AprobarType;
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
class AprobarController extends AbstractController
{
    /**
     * Displays a form to approve an existing suplemento entity.
     *
     * @Route("/{id<[1-9]\d*>}/approve",
     *      name="app_suplemento_approve",
     *      methods={"GET", "POST"})
     */
    public function approve(Request $request, Suplemento $suplemento): Response
    {
        $contrato = $suplemento->getContrato();
        $form = $this->createForm(AprobarType::class, $suplemento);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $estado = $em->getRepository(Estado::class) ->findOneBy(['estado' => 'APROBADO']);

            $suplemento->setEstado($estado);

            $this->addFlash('notice', 'Suplemento aprobado con exito!');
            $em->flush();

            return $this->render('common/notify.html.twig', [
                'redirect' => $this->generateUrl('app_suplemento_index', ['id' => $contrato->getId()])
            ]);
        }

        return $this->render('logistica/suplemento/modal/aprobar_form.html.twig', [
            'form' => $form->createView(),
            'suplemento' => $suplemento,
            'contrato' => $contrato,
        ]);
    }

    /**
     * Displays a form to approve suplemento entity.
     *
     * @Route("/{id<[1-9]\d*>}/not-approve",
     *      name="app_suplemento_not_approve",
     *      methods={"GET", "POST"}
     * )
     */
    public function notApprove(Suplemento $suplemento): Response
    {
        $em = $this->getDoctrine()->getManager();
        $estado = $em->getRepository(Estado::class)->findOneBy(['estado' => 'NO APROBADO']);
        $contrato = $suplemento->getContrato();

        $suplemento->setEstado($estado);

        $this->addFlash('notice', 'Suplemento no aprobado con exito!');

        $em->flush();

        return $this->render('common/notify.html.twig', [
            'redirect' => $this->generateUrl('app_suplemento_index', ['id' => $contrato->getId()])
        ]);
    }
}
