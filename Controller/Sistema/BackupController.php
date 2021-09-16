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
        $breadcrumb = [
            ['title' => 'Copia de seguridad'],
        ];

        $finder = new Finder();
        $finder->in($this->path);
        $finder->name('bd-nexus-*.backup');
        $finder->sortByModifiedTime();

        return $this->render('sistema/backup.html.twig',[
            'finder' => $finder,
            'breadcrumb' => $breadcrumb
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
     * @Route("/{file}/delete",
     *      name="app_backup_eliminar",
     *      methods={"GET"}
     * )
     */
    public function delete(string $file): Response
    {
        $fs = new Filesystem();

        if ($fs->exists($this->path . '/' . $file) && null !== $file) {
            $fs->remove($this->path . '/' . $file);
            $this->addFlash('notice', 'Backup eliminado con exito!');
            $this->log->register('DELETE');
        } else {
            $this->addFlash('error', 'No existe el archivo de backup!');
        }

        return $this->redirectToRoute('app_backup_index');
    }

    /**
     * @Route("/{file}/restore",
     *      name="app_backup_restaurar",
     *      methods={"GET"}
     * )
     */
    public function restore(string $file): Response
    {
        $fs = new Filesystem();

        if ($fs->exists($this->path . '/' . $file) && null !== $file) {
            $exec = '/bin/bash ' . __DIR__ . '/../../../bin/pgrestore.sh ' . $file;
            $process = new Process($exec);
            $process->setTimeout(0);
            $process->run();

            if (!$process->isSuccessful()) {
                throw new ProcessFailedException($process);
            }

            $this->addFlash('notice', 'Backup restaurado con exito!');
            $this->log->register('RESTORE');
        } else {
            $this->addFlash('error', 'No existe el archivo de backup!');
        }

        return $this->redirectToRoute('app_backup_index');
    }

    /**
     * @Route("/batch",
     *      name="app_backup_eliminar_batch",
     *      methods={"POST"}
     * )
     */
    public function batch(Request $request): Response
    {
        if (!$this->isCsrfTokenValid('bulk-action', $request->request->get('token'))
            || !$request->request->has('id')
        ) {
            $this->addFlash('error', 'Imposible ejecutar la acción, datos no validos o nulos');

            return $this->redirectToRoute('app_backup_index');
        }

        $data = $request->request->all();

        $files = $data['id'];
        $fs = new Filesystem();

        foreach ($files as $file) {
            if ($fs->exists($this->path . '/' . $file) && null !== $file) {
                $fs->remove($this->path . '/' . $file);
                $this->log->register('DELETE');
            }
        }

        $this->addFlash('notice', 'Acción en lotes ejecutada con exito!');

        return $this->redirectToRoute('app_backup_index');
    }
}
