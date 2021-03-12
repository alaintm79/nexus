<?php

namespace App\Controller\Sistema;

use App\Service\LogRegister;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Backup controller.
 *
 * @Route("sistema/backup")
 */
class BackupController extends AbstractController
{
    /** @var string $path */
    protected $path;

    /** @var LogRegister $log */
    protected $log;

    public function __construct(LogRegister $log)
    {
        $this->path = __DIR__.'/../../../public/dump';
        $this->log = $log;
    }
    /**
     * @Route("/",
     *      name="app_backup_index",
     *      methods={"GET"}
     * )
     */
    public function index(): Response
    {
        $finder = new Finder();
        $finder->in($this->path);
        $finder->name('bd-nexus-*.backup');
        $finder->sortByModifiedTime();

        $deleteForm = $this->createDeleteForm();
        $restoreForm = $this->createRestoreForm();

        return $this->render('sistema/backup/index.html.twig',[
            'finder' => $finder,
            'deleteForm' => $deleteForm->createView(),
            'restoreForm' => $restoreForm->createView(),
        ]);
    }

    /**
     * @Route("/create",
     *      name="app_backup_exportar",
     *      methods={"GET"}
     * )
     */
    public function export(): Response
    {
        $exec = '/bin/bash '.__DIR__.'/../../../bin/pgdump.sh';
        $process = new Process($exec);

        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        $this->log->register('CREATE');
        $this->addFlash('notice', 'Exportación realizada con exito!');

        return $this->redirectToRoute('app_backup_index');
    }

    /**
     * @Route("/delete",
     *      name="app_backup_eliminar",
     *      methods={"POST"}
     * )
     */
    public function delete(Request $request): Response
    {
        $deleteForm = $this->createDeleteForm();
        $deleteForm->handleRequest($request);

        if ($deleteForm->isSubmitted() && $deleteForm->isValid()) {
            $fs = new Filesystem();

            $file = $deleteForm->get('file_delete')->getData();

            if($fs->exists($this->path.'/'.$file) && null !== $file){
                $fs->remove($this->path.'/'.$file);
                $this->addFlash('notice', 'Backup eliminado con exito!');
                $this->log->register('DELETE');
            } else{
                $this->addFlash('error', 'No existe el archivo de backup!');
            }
        }

        return $this->redirectToRoute('app_backup_index');
    }

    /**
     * @Route("/restore",
     *      name="app_backup_restaurar",
     *      methods={"POST"}
     * )
     */
    public function restore(Request $request): Response
    {
        $restoreForm = $this->createRestoreForm();
        $restoreForm->handleRequest($request);

        if ($restoreForm->isSubmitted() && $restoreForm->isValid()) {
            $fs = new Filesystem();

            $file = $restoreForm->get('file_restore')->getData();

            if($fs->exists($this->path.'/'.$file) && null !== $file){
                $exec = '/bin/bash '.__DIR__.'/../../../bin/pgrestore.sh '.$file;
                $process = new Process($exec);
                $process->setTimeout(0);
                $process->run();

                if (!$process->isSuccessful()) {
                    throw new ProcessFailedException($process);
                }

                $this->addFlash('notice', 'Backup restaurado con exito!');
                $this->log->register('RESTORE');
            } else{
                $this->addFlash('error', 'No existe el archivo de backup!');
            }
        }

        return $this->redirectToRoute('app_backup_index');
    }

    /**
     * Crear formulario para eliminar backup
     */
    private function createDeleteForm()
    {
        return $this->createFormBuilder()
            ->setMethod('POST')
            ->setAction($this->generateUrl('app_backup_eliminar'))
            ->add('file_delete', HiddenType::class)
            ->getForm();
    }

    /**
     * Crear formulario para restaurar backup
     */
    private function createRestoreForm()
    {
        return $this->createFormBuilder()
            ->setMethod('POST')
            ->setAction($this->generateUrl('app_backup_restaurar'))
            ->add('file_restore', HiddenType::class)
            ->getForm();
    }
}
