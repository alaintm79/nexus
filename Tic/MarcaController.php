<?php

namespace App\Controller\Tic;

use App\Entity\Tic\Nomenclador\Marca;
use App\Form\Tic\Nomenclador\MarcaType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Repository\Tic\Nomenclador\MarcaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("tic/nomencladores/marcas")
 */
class MarcaController extends AbstractController
{
    /**
     * @Route("/", name="app_tic_nomenclador_marca_index")
     */
    public function index(): Response
    {
        $breadcrumb = [
            ['title' => 'Marcas']
        ];

        return $this->render('tic/marca.html.twig',[
            'breadcrumb' => $breadcrumb
        ]);
    }

    /**
     * @Route("/list",
     *      name="app_tic_nomenclador_marca_list",
     *      methods={"GET"}
     * )
     */
    public function list(MarcaRepository $marcas): Response
    {
        $marcas = $marcas->findAllMarcas();

        return new JsonResponse($marcas);
    }

    /**
     * Creates a new marca entity.
     *
     * @Route("/new",
     *      name="app_tic_nomenclador_marca_new",
     *      methods={"GET", "POST"}
     * )
     */
    public function new(Request $request): Response
    {
        $marca = new Marca();
        $form = $this->createForm(MarcaType::class, $marca);
        $breadcrumb = [
            ['title' => 'Marcas', 'url' => $this->generateUrl('app_tic_nomenclador_marca_index')],
            ['title' => 'Registrar']
        ];

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->persist($marca);
            $em->flush();

            $this->addFlash('notice', 'Marca registrada con exito!');

            if ($form->get('saveAndReturn')->isClicked()) {
                return $this->redirectToRoute('app_tic_nomenclador_marca_new');
            }

            return $this->redirectToRoute('app_tic_nomenclador_marca_index');
        }

        return $this->render('tic/form/marca_form.html.twig', [
            'form' => $form->createView(),
            'breadcrumb' => $breadcrumb
        ]);
    }

    /**
     * Displays a form to edit an existing marca entity.
     *
     * @Route("/{id}/edit",
     *      name="app_tic_nomenclador_marca_edit",
     *      methods={"GET", "POST"})
     */
    public function edit(Request $request, Marca $marca): Response
    {
        $form = $this->createForm(MarcaType::class, $marca);
        $breadcrumb = [
            ['title' => 'Marcas', 'url' => $this->generateUrl('app_tic_nomenclador_marca_index')],
            ['title' => 'Modificar']
        ];

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('notice', 'Marca modificada con exito!');

            return $this->redirectToRoute('app_tic_nomenclador_marca_index');
        }

        return $this->render('tic/form/marca_form.html.twig', [
            'form' => $form->createView(),
            'breadcrumb' => $breadcrumb
        ]);
    }
}
