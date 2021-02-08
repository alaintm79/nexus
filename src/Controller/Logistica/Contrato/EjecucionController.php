<?php

namespace App\Controller\Logistica\Contrato;

use App\Repository\Logistica\Contrato\ContratoRepository;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\Logistica\Contrato\EjecucionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

/**
 * Ejecucion controller.
 *
 * @Route("logistica/contrato/ejecuciones")
 *
 */
class EjecucionController extends AbstractController
{
    /**
     * Lists all ejecuciones entities.
     *
     * @Route("/{id}",
     *      name="app_contrato_ejecucion_index",
     *      methods={"GET"})
     */
    public function index(EjecucionRepository $ejecuciones, ContratoRepository $contrato, int $id): Response
    {
        return $this->render('logistica/contrato/modal/ejecucion.html.twig', [
            'contrato' => $contrato->findById($id),
            'ejecuciones' => $ejecuciones->findEjecucionByContratoId($id)
        ]);
    }
}
