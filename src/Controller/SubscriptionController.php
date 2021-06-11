<?php

namespace App\Controller;

use App\Entity\Subscription;
use App\Repository\SubscriptionRepository;
use App\Service\SubscribeUser;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class SubscribeController
 * @IsGranted("ROLE_USER")
 */
class SubscriptionController extends AbstractController
{
    /**
     * @var Subscription
     */
    private $currentSubscribe;
    /**
     * @var SubscriptionRepository
     */
    private $repository;

    /**
     * SubscribeController constructor.
     */
    public function __construct(SubscriptionRepository $repository)
    {
        $this->repository = $repository;

    }

    public function getCurrentSubscription()
    {
        return $this->repository->getCurrentSubscription($this->getUser());
    }


    /**
     * @Route("/personal/subscribe", name="app_personal_area_subscribe")
     */
    public function index(): Response
    {
        $currentSubscription = $this->getCurrentSubscription();
        $subscriptionName = SubscribeUser::getSubscriptionName($currentSubscription);
        return $this->render('subscription/index.html.twig', [
            'activeSubscribe' => $currentSubscription,
            'subscriptionName' => $subscriptionName
        ]);
    }

    /**
     * @Route("/personal/subscribe/change/{role}", name="app_personal_area_subscribe_change", requirements={"role":"ROLE_PLUS|ROLE_PRO"})
     * @throws \Exception
     */
    public function subscribe(string $role, SubscribeUser $subscribeUser)
    {
        $currentSubscription = $this->getCurrentSubscription();

        if ($subscribeUser->subscribe($this->getUser(), $currentSubscription, $role)) {
            $this->addFlash('flash_message','You have bought subscription');
            return $this->redirectToRoute('app_personal_area_subscribe');
        }
        return $this->render('subscription/index.html.twig', [
            'activeSubscribe' => $currentSubscription
        ]);
    }
}
