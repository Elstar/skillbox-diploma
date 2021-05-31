<?php


namespace App\EventSubscriber;


use App\Events\UserRegisteredEvent;
use App\Service\Mailer;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class UserRegisteredSubscriber implements EventSubscriberInterface
{
    /**
     * @var Mailer
     */
    private $mailer;


    /**
     * UserRegisteredSubscriber constructor.
     */
    public function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public function onUserRegistered(UserRegisteredEvent $event)
    {
        $this->mailer->sendConfirmEmail($event->getUser());
    }

    public static function getSubscribedEvents(): array
    {
        return [
            UserRegisteredEvent::class => 'onUserRegistered'
        ];
    }
}