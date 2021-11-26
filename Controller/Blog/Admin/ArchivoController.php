<?php

namespace App\Controller\Blog\Admin;

use App\Entity\Blog\Archivo;
use App\Form\Blog\Admin\ArchivoType;
use App\Repository\Blog\ArchivoRepository;
use App\Service\Cache;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 *  Intranet Modulos controller.
 *
 *
 *  @Route("blog/admin/archivos")
 */
class ArchivoController extends AbstractController
{
    protected $path;
    private const CACHE_ID = 'app_menu_archivo_cache';

    public function __construct()
    {
        $this->path = __DIR__.'/../../../../public/uploads/files';
    }

    /**
     * @Route("/", name="app_blog_admin_archivo_index")
     */
    public function index(): Response
    {
        $breadcrumb = [
            ['title' => 'Archivos']
        ];

        return $this->render('blog/admin/archivo.html.twig', [
            'breadcrumb' => $breadcrumb
        ]);
    }

    /**
     * @Route("/list",
     *      name="app_blog_admin_archivo_list",
     *      methods={"GET"}
     * )
     */
    public function list(ArchivoRepository $archivos): Response
    {
        return new JsonResponse($archivos->findAll());
    }

    /**
     * Creates a new archivo entity.
     *
     * @Route("/new",
     *      name="app_blog_admin_archivo_new",
     *      methods={"GET", "POST"}
     * )
     */
    public function new(Request $request, Cache $cache): Response
    {
        $archivo = new Archivo();
        $form = $this->createForm(ArchivoType::class, $archivo);
        $breadcrumb = [
            ['title' => 'Archivos', 'url' => $this->generateUrl('app_blog_admin_archivo_index')],
            ['title' => 'Registrar']
        ];

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $filesystem = new Filesystem();

            try {
                $filesystem->mkdir($this->path.'/'.$archivo->getRuta(), 0755);
            } catch (IOExceptionInterface $exception) {
                echo "An error occurred while creating your directory at ".$exception->getPath();
            }

            $em->persist($archivo);
            $em->flush();

            $cache->delete(self::CACHE_ID);

            $this->addFlash('notice', 'Archivo creado con exito!');

            if ($form->get('saveAndReturn')->isClicked()) {
                return $this->redirectToRoute('app_blog_admin_archivo_new');
            }

            return $this->redirectToRoute('app_blog_admin_archivo_index', []);
        }

        return $this->render('blog/admin/form/archivo_form.html.twig', [
            'form' => $form->createView(),
            'breadcrumb' => $breadcrumb
        ]);
    }

    /**
     * Displays a form to edit an existing archivo entity.
     *
     * @Route("/{id}/edit",
     *      name="app_blog_admin_archivo_edit",
     *      methods={"GET", "POST"})
     */
    public function edit(Request $request, Archivo $archivo, Cache $cache): Response
    {
        $active = $archivo->getRuta();
        $form = $this->createForm(ArchivoType::class, $archivo);
        $form->handleRequest($request);

        $breadcrumb = [
            ['title' => 'Archivos', 'url' => $this->generateUrl('app_blog_admin_archivo_index')],
            ['title' => 'Modificar']
        ];

        if ($form->isSubmitted() && $form->isValid()) {

            $filesystem = new Filesystem();
            $new = $form->getData()->getRuta();

            if($active !== $new){
                if ($filesystem->exists($this->path.'/'.$active)){
                    $filesystem->rename($this->path.'/'.$active, $this->path.'/'.$new);
                } else {
                    $filesystem->mkdir($this->path.'/'.$new, 0755);
                }
            }

            $this->addFlash('notice', 'Archivo modificado con exito!');

            $this->getDoctrine()->getManager()->flush();

            $cache->delete(self::CACHE_ID);

            return $this->redirectToRoute('app_blog_admin_archivo_index', []);
        }

        return $this->render('blog/admin/form/archivo_form.html.twig', [
            'form' => $form->createView(),
            'archivo' => $archivo,
            'breadcrumb' => $breadcrumb
        ]);
    }

    /**
     * Deletes a archivo entity.
     *
     * @Route("/{id}/delete",
     *      name="app_blog_admin_archivo_delete",
     *      methods={"GET", "POST"}
     * )
     */
    public function delete(Archivo $archivo, Cache $cache): Response
    {
            $filesystem = new Filesystem();
            $dir = $archivo->getRuta();

            if ($filesystem->exists($this->path.'/'.$dir)){
                $filesystem->remove($this->path.'/'.$dir);
            }

            $em = $this->getDoctrine()->getManager();
            $em->remove($archivo);
            $em->flush();

            $cache->delete(self::CACHE_ID);

            $this->addFlash('notice', 'Archivo eliminado con exito!');

            return $this->redirectToRoute('app_blog_admin_archivo_index', []);
    }
}
