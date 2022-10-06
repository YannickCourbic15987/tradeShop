<?php

namespace App\Services;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
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

    public function sendEmail(
        string $from,
        string $to,
        string $subject,
        string $template,
        array $context

    ): void {
        // $from = 'YannickCourbicTest@gmail.com';

        //on crée le mail en question

        $email = (new TemplatedEmail())
            ->from($from)
            ->to($to)
            ->subject($subject)
            ->htmlTemplate("emails/$template.html.twig")
            ->context($context);

        //on envoie le mail 

        $this->mailer->send($email);
    }
}
