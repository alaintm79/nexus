<?php

namespace App\Controller\Blog\Admin;

use App\Entity\Blog\Opcion;
use App\Form\Blog\Admin\OpcionType;
use App\Repository\Blog\OpcionRepository;
use App\Service\Cache;
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
    private const CACHE_ID = 'app_option_cache';
    private const CACHE_TTL = '300';

    /**
     * @Route("/", name="app_blog_admin_opcion_index")
     */
    public function index(OpcionRepository $opciones, Cache $cache): Response
    {
        $breadcrumb = [
            ['title' => 'Opciones']
        ];

        return $this->render('blog/admin/opcion.html.twig', [
            'opciones' => $this->convertToArray($opciones->findAllWithExcluded()),
            'breadcrumb' => $breadcrumb
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
     * @Route("/opcion/{id<[1-9]\d*>}/edit",
     *      name="app_blog_admin_opcion_edit",
     *      methods={"GET", "POST"}
     * )
     */
    public function opcionEdit(Request $request, Cache $cache, Opcion $opcion): Response
    {
        $form = $this->createForm(OpcionType::class, $opcion);
        $breadcrumb = [
            ['title' => 'Opciones', 'url' => $this->generateUrl('app_blog_admin_opcion_index')],
            ['title' => 'Modificar']
        ];

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->getDoctrine()->getManager()->flush();

            $cache->delete(self::CACHE_ID);

            $this->addFlash('notice', 'Opción modificada con exito!');

            return $this->redirectToRoute('app_blog_admin_opcion_index');
        }

        return $this->render('blog/admin/form/opcion_form.html.twig', [
            'form' => $form->createView(),
            'opcion' => $opcion,
            'breadcrumb' => $breadcrumb
        ]);
    }

    /**
     * Displays a form to edit an existing modulo entity.
     *
     * @Route("/media/{token}/edit",
     *      name="app_blog_admin_opcion_edit_inline",
     *      methods={"POST"}
     * )
     */
    public function mediaEdit(Request $request, Cache $cache, Opcion $opcion, $token): Response
    {
        if (!$this->isCsrfTokenValid($token, $request->request->get('token'))) {
            $this->addFlash('error', 'Imposible modificar la opción');
        }

        if ($this->isCsrfTokenValid($token, $request->request->get('token'))) {
            $valor = $request->request->get('valor');

            $opcion->setValor($valor);

            $this->getDoctrine()->getManager()->flush();

            $cache->delete(self::CACHE_ID);

            $this->addFlash('notice', 'Opción modificada con exito!');
        }

        return $this->redirectToRoute('app_blog_admin_opcion_index');
    }

    /**
     *  @inheritdoc
     */
    public function getOpciones(OpcionRepository $opciones, Cache $cache){

        if(empty($cache->get(self::CACHE_ID))){
            $cache->set(self::CACHE_ID, $this->convertToArray($opciones->findAll()), self::CACHE_TTL);
        }

        return new Response($cache->get(self::CACHE_ID));
    }

    /**
     *  @inheritdoc
     */
    private function convertToArray(array $opciones): ?array
    {
        $list = [];

        foreach($opciones as $item){
            $name = '';

            foreach($item as $key => $value){
                if($key === 'token'){
                    $list[$value]['token'] = $value;
                    $name = $value;
                }

                if($key === 'nombre'){
                    $list[$name]['nombre'] = $value;
                }

                if($key === 'isActive'){
                    $list[$name]['is_active'] = $value;
                }

                if($key === 'valor'){
                    $list[$name]['valor'] = $value;
                }
            }
        }

        return $list;
    }
}
