<?php

namespace App\Controller\Blog;

use App\Service\Cache;
use App\Repository\Blog\EnlaceRepository;
use App\Repository\Sistema\UsuarioRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 *  Intranet Modulos controller.
 */
class CumpleannosController extends AbstractController
{
    public function index(UsuarioRepository $usuarios): Response
    {
        return $this->render('blog/_cumpleannos.html.twig', [
            'usuarios' => $usuarios->findCumpleAnnosByCi()
        ]);
    }
}
