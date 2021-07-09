<?php

declare(strict_types=1);

namespace App\Auth\Command\ChangePassword;

class Command
{
    /**
     * User id
     * @var string
     */
    public string $id      = '';

    /**
     * Current user password
     * @var string
     */
    public string $current = '';

    /**
     * New user password
     * @var string
     */
    public string $new = '';
}
