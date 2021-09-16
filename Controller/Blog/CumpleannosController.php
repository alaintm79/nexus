<?php

namespace App\Controller\Blog;

use App\Repository\Blog\ViewCumpleannoRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

/**
 *  Intranet Modulos controller.
 */
class CumpleannosController extends AbstractController
{
    /**
     * @Cache(smaxage="300")
     */
    public function index(ViewCumpleannoRepository $usuarios): Response
    {
        return $this->render('blog/include/_cumpleannos.html.twig', [
            'usuarios' => $usuarios->findCumpleannos()
        ]);
    }
}
