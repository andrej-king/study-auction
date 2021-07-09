<?php

declare(strict_types=1);

namespace App\Auth\Command\JoinByNetwork;

class Command
{
    /**
     * Email for user from social network
     * @var string
     */
    public string $email = '';

    /**
     * Social network name
     * @var string
     */
    public string $network = '';

    /**
     * user id in social network
     * @var string
     */
    public string $identity = '';
}
