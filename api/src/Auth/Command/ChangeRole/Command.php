<?php

declare(strict_types=1);

namespace App\Auth\Command\ChangeRole;

class Command
{
    /**
     * User id
     * @var string
     */
    public string $id   = '';

    /**
     * New role
     * @var string
     */
    public string $role = '';
}
