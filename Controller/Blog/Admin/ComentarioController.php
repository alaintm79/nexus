<?php

namespace App\Controller\Blog\Admin;

use Exception;
use App\Entity\Blog\Comentario;
use App\Form\Blog\Admin\ComentarioType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\Blog\ComentarioRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 *  Comentario controller.
 *  @Route("blog/admin/comentarios")
 */
class ComentarioController extends AbstractController
{
    /**
     * @Route("/estado/{estado}",
     *      name="app_blog_admin_comentario_index",
     *      requirements={"estado": "pendiente|aprobado|eliminado"},
     *      methods={"GET"}
     * )
     */
    public function index(ComentarioRepository $comentarios, string $estado): Response
    {
        $breadcrumb = [
            ['title' => 'Comentarios']
        ];

        return $this->render('blog/admin/comentario.html.twig', [
            'total' => $comentarios->findTotalesByEstado(),
            'estado' => $estado,
            'breadcrumb' => $breadcrumb
        ]);
    }

    /**
     * @Route("/{estado}/list",
     *      name="app_blog_admin_comentario_list",
     *      requirements={"estado": "aprobado|pendiente|eliminado"},
     *      methods={"GET"}
     * )
     */
    public function list(Request $request, ComentarioRepository $comentarios, string $estado): Response
    {
        $params = $request->query->all();
        $total = $comentarios->findTotalComentariosByEstado($params, $estado);
        $comentarios = $comentarios->findComentariosByEstado($params, $estado);

        $result = [
            'total' => empty($comentarios) ? 0 : $total,
            'rows' => $comentarios
        ];

        return new JsonResponse($result);
    }

    /**
     * @Route("/{id<[1-9]\d*>}/edit",
     *      name="app_blog_admin_comentario_edit",
     *      methods={"GET", "POST"}
     * )
     */
    public function edit(Request $request, Comentario $comentario): Response
    {
        $form = $this->createForm(ComentarioType::class, $comentario);
        $breadcrumb = [
            [
                'title' => 'Comentarios',
                'url' => $this->generateUrl('app_blog_admin_comentario_index', [
                    'estado' => $comentario->getIsReview() ? 'aprobado' : 'pendiente'
                ])
            ],
            ['title' => 'Modificar']
        ];

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();

            $em->persist($comentario);
            $em->flush();

            $this->addFlash('notice', 'Comentario modificada con exito!');

            if ($form->get('saveAndReturn')->isClicked()) {
                return $this->redirectToRoute('app_blog_admin_comentario_edit', [
                    'id' => $comentario->getId(),
                ]);
            }

            return $this->redirectToRoute('app_blog_admin_comentario_index', [
                'estado' => $comentario->getIsReview() ? 'aprobado' : 'pendiente',
            ]);
        }

        return $this->render('blog/admin/form/comentario_form.html.twig', [
            'form' => $form->createView(),
            'comentario' => $comentario,
            'breadcrumb' => $breadcrumb
        ]);
    }

    /**
     * @Route("/{id<[1-9]\d*>}/delete",
     *      name="app_blog_admin_comentario_delete",
     *      methods={"GET"}
     * )
     */
    public function delete(Comentario $comentario): Response
    {
        $redirectTo = $comentario->getIsReview() ? 'aprobado' : 'pendiente';
        $em = $this->getDoctrine()->getManager();

        $comentario->setIsDelete(\true);
        $em->flush();

        $this->addFlash('notice', 'Comentario borrado con exito!');

        return $this->redirectToRoute('app_blog_admin_comentario_index', [
            'estado' => $redirectTo
        ]);
    }

    /**
     * @Route("/{id<[1-9]\d*>}/approve",
     *      name="app_blog_admin_comentario_approve",
     *      methods={"GET"}
     * )
     */
    public function approve(Comentario $comentario): Response
    {
        $redirectTo = $comentario->getIsReview() ? 'aprobado' : 'pendiente';
        $em = $this->getDoctrine()->getManager();

        $comentario->setIsReview(\true);
        $em->flush();

        $this->addFlash('notice', 'Comentario aprobado con exito!');

        return $this->redirectToRoute('app_blog_admin_comentario_index', [
            'estado' => $redirectTo
        ]);
    }

    /**
     * @Route("/{id<[1-9]\d*>}/remove",
     *      name="app_blog_admin_comentario_remove",
     *      methods={"GET", "POST"}
     * )
     */
    public function remove(Comentario $comentario): Response
    {
        $em = $this->getDoctrine()->getManager();

        $em->remove($comentario);
        $em->flush();

        $this->addFlash('notice', 'Comentario eliminado con exito!');

        return $this->redirectToRoute('app_blog_admin_comentario_index', [
            'estado' => 'eliminado'
        ]);
    }

    /**
     * @Route("/{id<[1-9]\d*>}/restore",
     *      name="app_blog_admin_comentario_restore",
     *      methods={"GET", "POST"}
     * )
     */
    public function restore(Comentario $comentario): Response
    {
        $redirectTo = $comentario->getIsReview() ? 'aprobado' : 'pendiente';
        $em = $this->getDoctrine()->getManager();

        $comentario->setIsDelete(false);
        $em->flush();

        $this->addFlash('notice', 'Comentario restaurado con exito!');

        return $this->redirectToRoute('app_blog_admin_comentario_index', [
            'estado' => $redirectTo
        ]);
    }

    /**
     * @Route("/batch",
     *      name="app_blog_admin_comentario_batch",
     *      methods={"POST"}
     * )
     */
    public function batch(Request $request, EntityManagerInterface $em): Response
    {
        $whitelist = [
            '/blog/admin/comentarios/estado/pendiente',
            '/blog/admin/comentarios/estado/aprobado',
            '/blog/admin/comentarios/estado/eliminado',
        ];

        $redirectTo = $request->request->get('redirect_to');

        if(!\in_array($redirectTo, $whitelist)){
            throw new Exception("Error de url de retorno ".$redirectTo, 1);
        }

        if (!$this->isCsrfTokenValid('bulk-action', $request->request->get('token'))
            || !$request->request->has('id')
        ) {
            $this->addFlash('error', 'Imposible ejecutar la acción, datos no validos o nulos');

            return new RedirectResponse($redirectTo);
        }

        $data = $request->request->all();

        $ids = implode(', ', $data['id']);
        $action = $data['action'];
        $batchSize = 20;
        $i = 1;

        $dql = 'SELECT c FROM App\Entity\Blog\Comentario c WHERE c.id IN ('.$ids.')';
        $q = $em->createQuery($dql);

        foreach ($q->toIterable() as $row) {
            if($action === 'eliminar'){
                $em->remove($row);
                $em->flush(); // Execute delete
            }elseif($action === 'borrar'){
                $row->setIsDelete(true);
            }elseif($action === 'aprobados'){
                $row->setIsReview(true);
            }elseif($action === 'pendientes'){
                $row->setIsReview(false);
            }elseif($action === 'restaurar'){
                $row->setIsDelete(false);
            }

            ++$i;

            if (($i % $batchSize) === 0) {
                $em->flush(); // Executes all updates.
                $em->clear(); // Detaches all objects from Doctrine!
            }
        }

        $em->flush();
        $em->clear();

        $this->addFlash('notice', 'Acción en lotes ejecutada con exito!');

        return new RedirectResponse($redirectTo);
    }
}
