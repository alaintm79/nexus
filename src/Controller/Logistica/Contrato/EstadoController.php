<?php

namespace App\Controller\Logistica\Contrato;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Repository\Logistica\Contrato\ContratoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Contrato controller.
 *
 * @Route("logistica/contrato/estado")
 *
 */
class EstadoController extends AbstractController
{
    /**
     * @Route("/{estado}",
     *      name="app_contrato_estado",
     *      requirements={"estado": "sin-vigencia|cancelado"},
     *      methods={"GET"}
     * )
     */
    public function index(string $estado): Response
    {
        $file = \str_replace('-', '_', $estado);
        return $this->render('logistica/contrato/'.$file.'.html.twig');
    }

    /**
     * @Route("/{estado}/list",
     *      name="app_contrato_estado_list",
     *      requirements={"tipo": "sin-vigencia|cancelado"},
     *      methods={"GET"}
     * )
     */
    public function list(ContratoRepository $contratos, string $estado): Response
    {
        return new JsonResponse($contratos->findContratosByEstado($estado));
    }
}
