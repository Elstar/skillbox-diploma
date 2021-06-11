<?php


namespace App\Service;


use App\Entity\Subscription;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

class SubscribeUser
{
    static $subscriptions = ['ROLE_PLUS', 'ROLE_PRO'];
    static $subscriptionNames = ['ROLE_FREE' => 'Free', 'ROLE_PLUS' => 'Plus', 'ROLE_PRO' => 'Pro'];
    /**
     * @var \DateTime
     */
    private $dateTo;
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * SubscribeUser constructor.
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->dateTo = new \DateTime('+1 week');
        $this->em = $em;
    }

    /**
     * @param User $user
     * @param $currentSubscription Subscription|array
     * @param string $subscriptionType
     * @param $dateTo
     * @return bool
     * @throws Exception
     */
    public function subscribe(
        User $user,
        $currentSubscription,
        string $subscriptionType,
        $dateTo = null
    ): bool {
        if (!in_array($subscriptionType, self::$subscriptions)) {
            throw new Exception('Wrong subscription type');
        }

        if (!empty($currentSubscription) && $currentSubscription->getRole() == $subscriptionType) {
            throw new Exception('Current subscription is same');
        } else {
            if (!empty($currentSubscription) && $currentSubscription->getRole() == 'ROLE_PRO') {
                throw new Exception('You cannot change PRO subscription to PLUS');
            } else {
                if (is_null($dateTo) || ! $dateTo instanceof \DateTime) {
                    $dateTo = $this->dateTo;
                }
                $newSubscription = new Subscription();
                $newSubscription
                    ->setUser($user)
                    ->setRole($subscriptionType)
                    ->setDateFrom(new \DateTime('now'))
                    ->setDateTo($dateTo);
                $this->em->persist($newSubscription);
                $this->em->flush();
                return true;
            }
        }
        return false;
    }

    /**
     * @param $subscription Subscription|array
     * @return string
     */
    static function getSubscriptionName($subscription): string
    {

        $role = (!empty($subscription)) ? $subscription->getRole() : 'ROLE_FREE';
        return self::$subscriptionNames[$role];
    }

}