<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Home controller.
 *
 * @Security("is_authenticated()")
 */
class HomeController extends AbstractController
{
    /**
     * @Route("/inicio", name="app_home")
     */
    public function index(): Response
    {
        return $this->render('home/index.html.twig');
    }
}
