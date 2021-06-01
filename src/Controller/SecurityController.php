<?php

namespace App\Controller;

use App\Entity\ApiToken;
use App\Entity\User;
use App\Events\UserRegisteredEvent;
use App\Form\Model\UserRegistrationFormModel;
use App\Form\UserRegistrationFormType;
use App\Security\LoginFormAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;


class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/register", name="app_register")
     */
    public function register(
        Request $request,
        UserPasswordEncoderInterface $passwordEncoder,
        AuthenticationUtils $authenticationUtils,
        EntityManagerInterface $em,
        EventDispatcherInterface $dispatcher
    ): Response {
        $form = $this->createForm(UserRegistrationFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /**
             * @var UserRegistrationFormModel $userModel
             */
            $userModel = $form->getData();
            $user = new User();
            $user->setEmail($userModel->email)
                ->setFirstName($userModel->firstName)
                ->setPassword($passwordEncoder->encodePassword($user, $userModel->plainPassword))
                ->setEmailConfirm(0)
                ->setEmailConfirmHash(md5(uniqid('confirm_' . $userModel->email, true)));
            $em->persist($user);
            $em->flush();

            $apiToken = new ApiToken($user);
            $em->persist($apiToken);
            $em->flush();

            $dispatcher->dispatch(new UserRegisteredEvent($user));

            return $this->render('security/register.html.twig', [
                'success' => true,
                'registrationForm' => $form->createView()
            ]);
        }

        $error = $authenticationUtils->getLastAuthenticationError();

        return $this->render('security/register.html.twig', [
            'error' => $error,
            'success' => false,
            'registrationForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/confirm/{email_confirm_hash}", name="app_email_confirm")
     * @param User $user
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param GuardAuthenticatorHandler $guard
     * @param LoginFormAuthenticator $authenticator
     * @return Response
     */
    public function confirm(
        User $user,
        Request $request,
        EntityManagerInterface $em,
        GuardAuthenticatorHandler $guard,
        LoginFormAuthenticator $authenticator
    ): Response {
        if (!is_null($user)) {
            if (!$user->getEmailConfirm()) {
                $user->setEmailConfirm(1);
                $em->persist($user);
                $em->flush();
            }

            return $guard->authenticateUserAndHandleSuccess(
                $user,
                $request,
                $authenticator,
                'main'
            );
        }
        return $this->render('security/confirm.html.twig');
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
