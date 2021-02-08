<?php

namespace App\Controller\Logistica\Contrato;

use App\Entity\Logistica\Contrato\Estado;
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
class SinVigenciaController extends AbstractController
{
    /**
     * Displays a form to cancel contrato entity.
     *
     * @Route("/{id<[1-9]\d*>}/sin-vigencia",
     *      name="app_contrato_sin_vigencia",
     *      methods={"GET", "POST"}
     * )
     */
    public function sinVigencia(Contrato $contrato): Response
    {
        $em = $this->getDoctrine()->getManager();
        $estado = $em->getRepository(Estado::class) ->findOneBy(['estado' => 'SIN VIGENCIA']);

        $contrato->setEstado($estado);
        $contrato->setIsModificable(false);

        $this->addFlash('notice', 'Contrato cambiado de estado con exito!');

        $em->flush();

        return $this->render('common/notify.html.twig', []);
    }
}
