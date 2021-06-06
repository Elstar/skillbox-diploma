<?php


namespace App\Service;


use App\Entity\User;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

class Mailer
{
    /**
     * @var MailerInterface
     */
    private $mailer;

    /**
     * Mailer constructor.
     */
    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendConfirmEmail(User $user)
    {
        $this->send('email/confirm.html.twig',
            'Подтверждение регистрации',
            $user,
            function (TemplatedEmail $email) use ($user) {
                $email
                    ->context([
                        'user' => $user
                    ])
                ;
            }
        );
    }

    /**
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     */
    private function send(string $template, string $subject, User $user, \Closure $callback = null)
    {
        $email = (new TemplatedEmail())
            ->from(new Address('noreplay@blablaarticle.com', 'Bla Bla Article'))
            ->to(new Address($user->getEmail(), $user->getFirstName()))
            ->subject($subject)
            ->htmlTemplate($template)
        ;
        if ($callback) {
            $callback($email);
        }
        $this->mailer->send($email);
    }
}