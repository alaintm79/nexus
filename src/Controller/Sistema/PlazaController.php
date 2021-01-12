<?php

namespace App\Controller\Sistema;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PlazaController extends AbstractController
{
    /**
     * @Route("/plaza", name="plaza")
     */
    public function index(): Response
    {
        return $this->render('plaza/index.html.twig', [
            'controller_name' => 'PlazaController',
        ]);
    }
}
