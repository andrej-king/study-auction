<?php

declare(strict_types=1);

namespace App\Auth\Service;

use App\Auth\Entity\User\Email;
use App\Auth\Entity\User\Token;

interface PasswordResetTokenSender
{
    /**
     * Send mail with token
     * @param Email $email
     * @param Token $token
     */
    public function send(Email $email, Token $token): void;
}
