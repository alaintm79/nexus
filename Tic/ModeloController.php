<?php

namespace App\Controller\Tic;

use App\Entity\Tic\Nomenclador\Modelo;
use App\Form\Tic\Nomenclador\ModeloType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Repository\Tic\Nomenclador\ModeloRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("tic/nomencladores/modelos")
 */
class ModeloController extends AbstractController
{
    /**
     * @Route("/", name="app_tic_nomenclador_modelo_index")
     */
    public function index(): Response
    {
        $breadcrumb = [
            ['title' => 'Modelos']
        ];

        return $this->render('tic/modelo.html.twig',[
            'breadcrumb' => $breadcrumb
        ]);
    }

    /**
     * @Route("/list",
     *      name="app_tic_nomenclador_modelo_list",
     *      methods={"GET"}
     * )
     */
    public function list(ModeloRepository $modelos): Response
    {
        $modelos = $modelos->findAllModelos();

        return new JsonResponse($modelos);
    }

    /**
     * Creates a new modelo entity.
     *
     * @Route("/new",
     *      name="app_tic_nomenclador_modelo_new",
     *      methods={"GET", "POST"}
     * )
     */
    public function new(Request $request): Response
    {
        $modelo = new Modelo();
        $form = $this->createForm(ModeloType::class, $modelo);
        $breadcrumb = [
            ['title' => 'Modelos', 'url' => $this->generateUrl('app_tic_nomenclador_modelo_index')],
            ['title' => 'Registrar']
        ];

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->persist($modelo);
            $em->flush();

            $this->addFlash('notice', 'Modelo registrado con exito!');

            if ($form->get('saveAndReturn')->isClicked()) {
                return $this->redirectToRoute('app_tic_nomenclador_modelo_new');
            }

            return $this->redirectToRoute('app_tic_nomenclador_modelo_index');
        }

        return $this->render('tic/form/modelo_form.html.twig', [
            'form' => $form->createView(),
            'breadcrumb' => $breadcrumb
        ]);
    }

    /**
     * Displays a form to edit an existing modelo entity.
     *
     * @Route("/{id}/edit",
     *      name="app_tic_nomenclador_modelo_edit",
     *      methods={"GET", "POST"})
     */
    public function edit(Request $request, Modelo $modelo): Response
    {
        $form = $this->createForm(ModeloType::class, $modelo);
        $breadcrumb = [
            ['title' => 'Modelos', 'url' => $this->generateUrl('app_tic_nomenclador_modelo_index')],
            ['title' => 'Modificar']
        ];

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('notice', 'Modelo modificado con exito!');

            return $this->redirectToRoute('app_tic_nomenclador_modelo_index');
        }

        return $this->render('tic/form/modelo_form.html.twig', [
            'form' => $form->createView(),
            'breadcrumb' => $breadcrumb,
            'modelo' => $modelo
        ]);
    }
}
