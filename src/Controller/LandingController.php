<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LandingController extends AbstractController
{
    /**
     * @Route("/", name="app_landing_home")
     */
    public function index(): Response
    {
        return $this->render('landing/index.html.twig', [
        ]);
    }

    /**
     * @Route("/try", name="app_landing_try")
     */
    public function tryPage(): Response
    {
        return $this->render('landing/try.html.twig', [
        ]);
    }
}
