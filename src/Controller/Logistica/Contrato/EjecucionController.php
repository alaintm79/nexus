<?php

namespace App\Controller\Logistica\Contrato;

use App\Repository\Logistica\Contrato\ContratoRepository;
use App\Repository\Logistica\Contrato\EjecucionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Ejecucion controller.
 *
 * @Route("logistica/contrato/ejecucion")
 *
 */
class EjecucionController extends AbstractController
{
    /**
     * Lists all ejecuciones entities.
     *
     * @Route("/{id<[1-9]\d*>}",
     *      name="app_contrato_ejecucion",
     *      methods={"GET"})
     */
    public function index(ContratoRepository $contrato, int $id): Response
    {
        return $this->render('logistica/contrato/modal/ejecucion_show.html.twig', [
            'contrato' => $contrato->findById($id),
        ]);
    }

    /**
     * @Route("/contrato/{id<[1-9]\d*>}/list",
     *      name="app_ejecucion_list",
     *      methods={"GET"}
     * )
     */
    public function list(EjecucionRepository $ejecucion, int $id): Response
    {
        return new JsonResponse($ejecucion->findEjecucionByContratoId($id));
    }
}
