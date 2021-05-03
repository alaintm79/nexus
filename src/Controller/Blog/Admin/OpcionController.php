<?php

namespace App\Controller\Blog\Admin;

use App\Entity\Blog\Opcion;
use App\Form\Blog\OpcionType;
use App\Repository\Blog\OpcionRepository;
use App\Service\OptionCache;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 *  Opcion Modulos controller.
 *
 *  @Route("blog/admin/opciones")
 */
class OpcionController extends AbstractController
{
    /**
     * @Route("/", name="app_blog_admin_opcion_index")
     */
    public function index(OpcionRepository $opciones, OptionCache $optionCache): Response
    {
        return $this->render('blog/admin/opcion.html.twig', [
            'opciones' => $optionCache->convertToArray($opciones->findAllWithExcluded()),
        ]);
    }

    /**
     * @Route("/list",
     *      name="app_blog_admin_opcion_list",
     *      methods={"GET"}
     * )
     */
    public function list(OpcionRepository $opciones): Response
    {
        return new JsonResponse($opciones->findAllButNotExcluded());
    }

    /**
     * Displays a form to edit an existing modulo entity.
     *
     * @Route("/{id<[1-9]\d*>}/edit",
     *      name="app_blog_admin_opcion_edit",
     *      methods={"GET", "POST"}
     * )
     */
    public function edit(Request $request, OptionCache $optionCache, Opcion $opcion): Response
    {
        $form = $this->createForm(OpcionType::class, $opcion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->getDoctrine()->getManager()->flush();

            $optionCache->delete();

            $this->addFlash('notice', 'Opción modificada con exito!');

            return $this->render('common/notify.html.twig', []);
        }

        return $this->render('blog/admin/modal/opcion_form.html.twig', [
            'form' => $form->createView(),
            'opcion' => $opcion
        ]);
    }

    /**
     * Displays a form to edit an existing modulo entity.
     *
     * @Route("/{token}/edit-inline",
     *      name="app_blog_admin_opcion_edit_inline",
     *      methods={"POST"}
     * )
     */
    public function editInLine(Request $request, OptionCache $optionCache, Opcion $opcion, $token): Response
    {
        if (!$this->isCsrfTokenValid($token, $request->request->get('token'))) {
            $this->addFlash('error', 'Imposible modificar la opción');
        }

        if ($this->isCsrfTokenValid($token, $request->request->get('token'))) {
            $valor = $request->request->get('valor');

            $opcion->setValor($valor);

            $this->getDoctrine()->getManager()->flush();

            $optionCache->delete();

            $this->addFlash('notice', 'Opción modificada con exito!');
        }

        return $this->redirectToRoute('app_blog_admin_opcion_index');
    }
}
