<?php

namespace App\Controller\Logistica\Contrato;

use App\Entity\Logistica\Contrato\Estado;
use App\Entity\Logistica\Contrato\Contrato;
use App\Form\Logistica\Contrato\AprobarType;
use App\Form\Logistica\Contrato\ContratoType;
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
class AprobarController extends AbstractController
{
    /**
     * Displays a form to approve contrato entity.
     *
     * @Route("/{id<[1-9]\d*>}/approve",
     *      name="app_contrato_approve",
     *      methods={"GET", "POST"}
     * )
     */
    public function approve(Request $request, Contrato $contrato): Response
    {
        $form = $this->createForm(AprobarType::class, $contrato);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            if(null === $contrato->getNumero()){
                $active = $em->getRepository(Contrato::class)->findUltimoContrato($contrato->getTipo());
                $estado = $em->getRepository(Estado::class) ->findOneBy(['estado' => 'APROBADO']);

                $contrato->setNumero($active, $contrato->getTipo());
                $contrato->setEstado($estado);
            }

            $this->addFlash('notice', 'Contrato aprobado con exito!');

            $em->flush();

            return $this->render('common/notify.html.twig', []);
        }

        return $this->render('logistica/contrato/modal/aprobar_form.html.twig', [
            'form' => $form->createView(),
            'contrato' => $contrato,
        ]);
    }
}
