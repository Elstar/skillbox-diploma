<?php


namespace App\Service;


use App\Entity\Subscribe;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class SubscribeUser
{
    static $subscribes = ['ROLE_PLUS', 'ROLE_PRO'];
    public $errorMessage;
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
     * @param string $newSubscribe
     * @param $currentSubscribe Subscribe|array
     * @return bool
     */
    public function checkSubscribe(string $newSubscribe, $currentSubscribe): bool
    {
        if (!in_array($newSubscribe, self::$subscribes)) {
            $this->errorMessage = 'Wrong subscribe type';
            return false;
        }

        if (!empty($currentSubscribe) && $currentSubscribe->getRole() == $newSubscribe) {
            $this->errorMessage = 'Current subscribe is same';
            return false;
        } else {
            if (!empty($currentSubscribe) && $currentSubscribe->getRole() == 'ROLE_PRO') {
                $this->errorMessage = 'You cannot change PRO subscribe to PLUS';
                return false;
            } else {
                return true;
            }
        }

    }

    /**
     * @param User $user
     * @param $currentSubscribe Subscribe|array
     * @param string $subscribeType
     * @param $dateTo
     * @return bool
     */
    public function subscribe(
        User $user,
        $currentSubscribe,
        string $subscribeType,
        $dateTo = null
    ): bool {
        if (!in_array($subscribeType, self::$subscribes)) {
            $this->errorMessage = 'Wrong subscribe type';
            return false;
        }
        if (!empty($currentSubscribe)) {
            $currentSubscribe->setDateTo(new \DateTime('now'));
            $this->em->persist($currentSubscribe);
            $this->em->flush();
        }
        if (is_null($dateTo) || ! $dateTo instanceof \DateTime) {
            $dateTo = $this->dateTo;
        }
        $newSubscribe = new Subscribe();
        $newSubscribe
            ->setUser($user)
            ->setRole($subscribeType)
            ->setDateFrom(new \DateTime('now'))
            ->setDateTo($dateTo);
        $this->em->persist($newSubscribe);
        $this->em->flush();
        return true;
    }

}