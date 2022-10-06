<?php

namespace App\Services;

use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

class MailerServices
{


    public function __construct(
        MailerInterface $mailer,
        Security $security,
    ) {
        $this->mailer = $mailer;
        $this->security = $security;
    }

    public function sendEmail($to): void
    {
        // $from = 'YannickCourbicTest@gmail.com';

        $email = (new Email())
            ->from('YannickCourbicTest@gmail.com')
            ->to($to)
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject('Time for Symfony Mailer!')
            ->text('Sending emails is fun again!')
            ->html('<p>See Twig integration for better HTML integration!</p>');

        try {
            $this->mailer->send($email);
        } catch (TransportExceptionInterface $e) {
            echo $e;
            // some error prevented the email sending; display an
            // error message or try to resend the message
        }
    }
}
