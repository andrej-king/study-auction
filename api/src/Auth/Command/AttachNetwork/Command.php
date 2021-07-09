<?php

declare(strict_types=1);

namespace App\Auth\Command\AttachNetwork;

class Command
{
    /**
     * User id
     * @var string
     */
    public string $id       = '';

    /**
     * Social network name
     * @var string
     */
    public string $network  = '';

    /**
     * User id in social network
     * @var string
     */
    public string $identity = '';
}
