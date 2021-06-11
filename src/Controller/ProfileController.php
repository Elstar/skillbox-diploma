<?php

namespace App\Controller;

use App\Entity\ApiToken;
use App\Entity\User;
use App\Form\UserEditFormType;
use App\Repository\ApiTokenRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ProfileController extends AbstractController
{
    /**
     * @Route("/personal/profile", name="app_personal_area_profile")
     */
    public function index(Request $request, UserPasswordEncoderInterface $passwordEncoder, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();

        $form = $this->createForm(UserEditFormType::class, $user);

        $form->handleRequest($request);
        $success = 0;

        if ($form->isSubmitted() && $form->isValid()) {
            /**
             * @var User $userModel
             */
            $userModel = $form->getData();

            if (empty($userModel->getPassword())) {
                $userModel->setPassword($user->getPassword());
            } else {
                $userModel->setPassword($passwordEncoder->encodePassword($userModel, $userModel->getPassword()));
            }

            $em->persist($userModel);
            $em->flush();
            $success = 1;
        }

        return $this->render('personal_area/profile.html.twig',[
            'success' => $success,
            'editProfileForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/personal/profile/getNewToken", name="app_personal_area_profile_get_new_token")
     */
    public function newToken(ApiTokenRepository $tokenRepository, EntityManagerInterface $em)
    {
        $token = $tokenRepository->getToken($this->getUser());

        if (!empty($token)) {
            $em->remove($token);
            $em->flush();
        }

        $newToken = new ApiToken($this->getUser());
        $em->persist($newToken);
        $em->flush();
    }
}
