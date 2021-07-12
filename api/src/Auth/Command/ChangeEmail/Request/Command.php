<?php

declare(strict_types=1);

namespace App\Auth\Command\ChangeEmail\Request;

class Command
{
    /**
     * User id
     * @var string
     */
    public string $id    = '';

    /**
     * New email value
     * @var string
     */
    public string $email = '';
}
