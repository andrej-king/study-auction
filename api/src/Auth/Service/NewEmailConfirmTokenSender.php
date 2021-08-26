<?php

declare(strict_types=1);

namespace App\Auth\Service;

use App\Auth\Entity\User\{Email, Token};
use RuntimeException;
use Swift_Mailer;
use Swift_Message;
use Twig\Environment;
use Twig\Error\{LoaderError, RuntimeError, SyntaxError};

class NewEmailConfirmTokenSender
{
    private Swift_Mailer $mailer;
    private Environment $twig;

    /**
     * @param Swift_Mailer $mailer
     * @param Environment  $twig
     */
    public function __construct(Swift_Mailer $mailer, Environment $twig)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
    }

    /**
     * Send email with confirm token
     *
     * @param Email $email
     * @param Token $token
     *
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function send(Email $email, Token $token): void
    {
        $message = (new Swift_Message('New Email Confirmation'))
            ->setTo($email->getValue())
            ->setBody($this->twig->render('auth/email/confirm.html.twig', ['token' => $token]), 'text/html');

        if ($this->mailer->send($message) === 0) {
            throw new RuntimeException('Unable to send email.');
        }
    }
}
