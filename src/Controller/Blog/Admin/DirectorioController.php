<?php

namespace App\Controller\Blog\Admin;

use App\Entity\Blog\Directorio;
use App\Form\Blog\DirectorioType;
use Symfony\Component\Filesystem\Filesystem;
use App\Repository\Blog\DirectorioRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 *  Intranet Modulos controller.
 *
 *
 *  @Route("blog/admin/directorios")
 */
class DirectorioController extends AbstractController
{
    protected $path;

    public function __construct()
    {
        $this->path = __DIR__.'/../../../../public/assets/files';
    }

    /**
     * @Route("/", name="app_blog_admin_directorio_index")
     */
    public function index(): Response
    {
        return $this->render('blog/admin/directorio.html.twig');
    }

    /**
     * @Route("/list",
     *      name="app_blog_admin_directorio_list",
     *      methods={"GET"}
     * )
     */
    public function read (DirectorioRepository $directorios): Response
    {
        return new JsonResponse($directorios->findAll());
    }

    /**
     * Creates a new directorio entity.
     *
     * @Route("/new",
     *      name="app_blog_admin_directorio_new",
     *      methods={"GET", "POST"}
     * )
     */
    public function new(Request $request): Response
    {
        $directorio = new Directorio();
        $form = $this->createForm(DirectorioType::class, $directorio);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $filesystem = new Filesystem();

            try {
                $filesystem->mkdir($this->path.'/'.$directorio->getRuta(), 0755);
            } catch (IOExceptionInterface $exception) {
                echo "An error occurred while creating your directory at ".$exception->getPath();
            }

            $em->persist($directorio);
            $em->flush();

            $this->addFlash('notice', 'Directorio creado con exito!');

            return $this->render('common/notify.html.twig', []);
        }

        return $this->render('blog/admin/modal/directorio_form.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Displays a form to edit an existing directorio entity.
     *
     * @Route("/{id}/edit",
     *      name="app_blog_admin_directorio_edit",
     *      methods={"GET", "POST"})
     */
    public function edit(Request $request, Directorio $directorio): Response
    {
        $active = $directorio->getRuta();
        $form = $this->createForm(DirectorioType::class, $directorio);
        $form->handleRequest($request);

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

            $this->addFlash('notice', 'Directorio modificado con exito!');

            $this->getDoctrine()->getManager()->flush();

            return $this->render('common/notify.html.twig', []);
        }

        return $this->render('blog/admin/modal/directorio_form.html.twig', [
            'form' => $form->createView(),
            'directorio' => $directorio
        ]);
    }

    /**
     * Deletes a directorio entity.
     *
     * @Route("/{id}/delete",
     *      name="app_blog_admin_directorio_delete",
     *      methods={"GET", "POST"}
     * )
     */
    public function delete(Request $request, Directorio $directorio): Response
    {
        if($request->isMethod('POST')){

            if (!$this->isCsrfTokenValid('delete', $request->request->get('token'))) {
                $this->addFlash('error', 'Imposible eliminar directorio');

                return $this->render('common/notify.html.twig', []);
            }

            $filesystem = new Filesystem();

            $dir = $directorio->getRuta();

            if ($filesystem->exists($this->path.'/'.$dir)){
                $filesystem->remove($this->path.'/'.$dir);
            }

            $em = $this->getDoctrine()->getManager();
            $em->remove($directorio);
            $em->flush();

            $this->addFlash('notice', 'Directorio eliminado con exito!');

            return $this->render('common/notify.html.twig', []);
        }

        return $this->render('blog/admin/modal/directorio_delete.html.twig', [
            'directorio' => $directorio,
        ]);
    }
}
