<?php

namespace App\Controller;

use App\Entity\Subscribe;
use App\Repository\SubscribeRepository;
use App\Service\SubscribeUser;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class SubscribeController
 * @IsGranted("ROLE_USER")
 */
class SubscribeController extends AbstractController
{
    /**
     * @var Subscribe
     */
    private $currentSubscribe;
    /**
     * @var SubscribeRepository
     */
    private $repository;

    /**
     * SubscribeController constructor.
     */
    public function __construct(SubscribeRepository $repository)
    {
        $this->repository = $repository;

    }

    public function setCurrentSubscribe()
    {
        $this->currentSubscribe = $this->repository->getCurrentSubscribe($this->getUser());
    }


    /**
     * @Route("/personal/subscribe", name="app_personal_area_subscribe")
     */
    public function index(): Response
    {
        $this->setCurrentSubscribe();

        return $this->render('subscribe/index.html.twig', [
            'activeSubscribe' => $this->currentSubscribe
        ]);
    }

    /**
     * @Route("/personal/subscribe/change/{role}", name="app_personal_area_subscribe_change", requirements={"role":"ROLE_PLUS|ROLE_PRO"})
     */
    public function subscribe(string $role, SubscribeUser $subscribeUser)
    {
        $this->setCurrentSubscribe();
        if ($subscribeUser->checkSubscribe($role, $this->currentSubscribe)) {
            if ($subscribeUser->subscribe($this->getUser(), $this->currentSubscribe, $role)) {
                $this->addFlash('flash_message','You have bought subscribe');
                return $this->redirectToRoute('app_personal_area_subscribe');
            }
        }
        $this->addFlash('flash_message_error', $subscribeUser->errorMessage);
        return $this->render('subscribe/index.html.twig', [
            'activeSubscribe' => $this->currentSubscribe
        ]);
    }
}
