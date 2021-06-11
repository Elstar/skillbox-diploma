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
use Symfony\Contracts\Translation\TranslatorInterface;

class ProfileController extends AbstractController
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * ProfileController constructor.
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }


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
            $formData = $request->request->get('user_edit_form');
            if (!empty($formData['plainPassword']['first'])) {
                $userModel->setPassword($passwordEncoder->encodePassword($userModel, $formData['plainPassword']['first']));
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
    public function getNewToken(ApiTokenRepository $tokenRepository, EntityManagerInterface $em)
    {
        /**
         * @var User $user
         */
        $user = $this->getUser();
        $currentToken = $user->getApiToken();
        $currentToken->setToken($user);

        $em->persist($currentToken);
        $em->flush();

        $this->addFlash('flash_message', $this->translator->trans('New Token have generated'));

        return $this->redirectToRoute('app_personal_area_profile');
    }
}
