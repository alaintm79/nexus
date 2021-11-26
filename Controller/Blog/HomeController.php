<?php

namespace App\Controller\Blog;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    /**
     * @Route("/blog/", name="app_blog_home")
     * @Cache(smaxage="300")
     */
    public function index(Request $request): Response
    {
        return $this->render('blog/home.html.twig', []);
    }
}
