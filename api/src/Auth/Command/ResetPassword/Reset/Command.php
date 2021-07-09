<?php

declare(strict_types=1);

namespace App\Auth\Command\ResetPassword\Reset;

class Command
{
    /**
     * Token from user
     * @var string
     */
    public string $token    = '';

    /**
     * New password from user
     * @var string
     */
    public string $password = '';
}
