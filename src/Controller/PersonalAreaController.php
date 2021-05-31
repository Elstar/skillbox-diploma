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
        return $this->render('personal_area/index.html.twig', [
            'controller_name' => 'PersonalAreaController',
        ]);
    }
}
