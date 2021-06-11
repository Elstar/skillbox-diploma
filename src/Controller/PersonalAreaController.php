<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class PersonalAreaController
 * @IsGranted("ROLE_USER")
 */
class PersonalAreaController extends AbstractController
{
    /**
     * @Route("/personal/area", name="app_personal_area")
     */
    public function index(): Response
    {
        return $this->render('personal_area/index.html.twig');
    }

    /**
     * @Route("/personal/create", name="app_personal_area_create")
     */
    public function create(): Response
    {
        return $this->render('personal_area/create.html.twig');
    }

    /**
     * @Route("/personal/history", name="app_personal_area_history")
     */
    public function history(): Response
    {
        return $this->render('personal_area/history.html.twig');
    }

    /**
     * @Route("/personal/generator", name="app_personal_area_generator")
     */
    public function generator(): Response
    {
        return $this->render('personal_area/generator.html.twig');
    }
}
