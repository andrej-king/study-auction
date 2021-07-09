<?php

declare(strict_types=1);

namespace App\Auth\Entity\User;

use Webmozart\Assert\Assert;

class NetworkIdentity
{
    /**
     * Social network name
     */
    private string $network;


    /**
     * Social network user id
     */
    private string $identity;

    /**
     * NetworkIdentity constructor.
     * @param string $network
     * @param string $identity
     */
    public function __construct(string $network, string $identity)
    {
        Assert::notEmpty($network);
        Assert::notEmpty($identity);
        $this->network = mb_strtolower($network);
        $this->identity = mb_strtolower($identity);
    }

    /**
     * Check that the social network is not linked to a person
     * @param NetworkIdentity $network
     * @return bool
     */
    public function isEqualTo(self $network): bool
    {
        return
            $this->getNetwork() === $network->getNetwork() &&
            $this->getIdentity() === $network->getIdentity();
    }

    /**
     * @return string
     */
    public function getNetwork(): string
    {
        return $this->network;
    }

    /**
     * @return string
     */
    public function getIdentity(): string
    {
        return $this->identity;
    }
}
