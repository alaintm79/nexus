<?php

namespace App\Controller\Logistica\Suplemento;

use App\Entity\Logistica\Contrato\Estado;
use App\Entity\Logistica\Contrato\Suplemento;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\Logistica\Suplemento\SuplementoType;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Repository\Logistica\Contrato\ContratoRepository;
use App\Repository\Logistica\Contrato\SuplementoRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Suplemento controller.
 *
 * @Route("logistica/suplemento")
 *
 */
class SuplementoController extends AbstractController
{
    /**
     * Lists all suplementos entities.
     *
     * @Route("/contrato/{id<[1-9]\d*>}",
     *      name="app_suplemento_index",
     *      methods={"GET"}
     * )
     */
    public function index(ContratoRepository $contrato, int $id): Response
    {
        return $this->render('logistica/suplemento/index.html.twig', [
            'contrato' => $contrato->findById($id),
            // 'suplementos' => $suplementos->findByContratoId($contrato)
        ]);
    }

    /**
     * @Route("/contrato/{contrato<[1-9]\d*>}/list",
     *      name="app_suplemento_list",
     *      methods={"GET"}
     * )
     */
    public function list(SuplementoRepository $suplementos, int $contrato): Response
    {
        return new JsonResponse($suplementos->findByContratoId($contrato));
    }

    /**
     * Creates a new suplemento entity.
     *
     * @Route("/contrato/{id<[1-9]\d*>}/new",
     *      name="app_suplemento_new",
     *      methods={"GET", "POST"})
     */
    public function new(Request $request, ContratoRepository $contrato, int $id): Response
    {
        $contrato = $contrato->findById($id);
        $suplemento = new Suplemento();
        $form = $this->createForm(SuplementoType::class, $suplemento);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $estado = $em->getRepository(Estado::class)->findOneBy(['estado' => 'REVISION']);

            $suplemento->setContrato($contrato);
            $suplemento->setTipo($contrato->getTipo());
            $suplemento->setEstado($estado);

            $this->addFlash('notice', 'Suplemento registrado con exito!');

            $em->persist($suplemento);
            $em->flush();

            return $this->render('common/notify.html.twig', [
                'redirect' => $this->generateUrl('app_suplemento_index', ['id' => $contrato->getId()])
            ]);
        }

        return $this->render('logistica/suplemento/modal/suplemento_form.html.twig', [
            'contrato' => $contrato,
            'suplemento' => $suplemento,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing suplemento entity.
     *
     * @Route("/{id<[1-9]\d*>}/edit",
     *      name="app_suplemento_edit",
     *      methods={"GET", "POST"})
     */
    public function edit(Request $request, Suplemento $suplemento): Response
    {
        $contrato = $suplemento->getContrato();
        $form = $this->createForm(SuplementoType::class, $suplemento);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->addFlash('notice', 'Suplemento modificado con exito!');

            $this->getDoctrine()->getManager()->flush();

            return $this->render('common/notify.html.twig', [
                'redirect' => $this->generateUrl('app_suplemento_index', ['id' => $contrato->getId()])
            ]);
        }


        return $this->render('logistica/suplemento/modal/suplemento_form.html.twig', [
            'form' => $form->createView(),
            'suplemento' => $suplemento,
            'contrato' => $contrato,
        ]);
    }

    /**
     * Finds and displays a suplemento entity.
     *
     * @Route("/{suplemento_id<[1-9]\d*>}/show",
     *      name="app_suplemento_show",
     *      methods={"GET"}
     * )
     * @Entity("suplemento", expr="repository.findById(suplemento_id)")
     */
    public function show(Suplemento $suplemento, ContratoRepository $contrato): Response
    {
        return $this->render('logistica/suplemento/modal/suplemento_show.html.twig', [
            'suplemento' => $suplemento,
            'contrato' => $contrato->findById($suplemento->getContrato()->getId()),
        ]);
    }

    // private function setValoresEjecucion($contrato, $cup, $cuc): void
    // {
    //     if(!is_null($cup)){
    //         $contrato->setValorEjecucionCup($contrato->getValorEjecucionCup() + $cup);
    //         $contrato->setValorTotalCup($contrato->getValorTotalCup() + $cup);
    //     }

    //     if(!is_null($cuc)){
    //         $contrato->setValorEjecucionCuc($contrato->getValorEjecucionCuc() + $cuc);
    //         $contrato->setValorTotalCuc($contrato->getValorTotalCuc() + $cuc);
    //     }
    // }

    // private function setVigencia($contrato, $suplemento, $firma, $vigencia, $fechaVigencia): void
    // {
    //     if(!is_null($vigencia)
    //         && $vigencia->getVigencia() !== 'CUMPLIMIENTO OBLIGACIONES'
    //         && $vigencia->getVigencia() !== 'CUMPLIMIENTO FECHA'
    //         && $vigencia->getVigencia() !== 'PERMANENTE'
    //     ){
    //         $vigenciaNew = $vigencia->getVigencia();
    //         $fechaVigencia = new \DateTime($firma);

    //         $suplemento->setFechaVigencia($fechaVigencia->modify(Vigencia::calcular($vigenciaNew)));
    //         $contrato->setFechaVigencia($suplemento->getFechaVigencia());
    //     }

    //     if(!is_null($fechaVigencia)){
    //         $contrato->setFechaVigencia($suplemento->getFechaVigencia());
    //     }
    // }
}
