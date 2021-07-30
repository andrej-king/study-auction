<?php

declare(strict_types=1);

namespace App\Auth\Entity\User;

use Doctrine\ORM\Mapping as ORM;
use Webmozart\Assert\Assert;

/**
 * @ORM\Embeddable
 */
class Network
{
    /**
     * Social network name
     * @ORM\Column(type="string", length=16)
     */
    private string $name;


    /**
     * Social network user id
     * @ORM\Column(type="string", length=16)
     */
    private string $identity;

    /**
     * NetworkIdentity constructor.
     * @param string $name
     * @param string $identity
     * @noinspection PhpFieldAssignmentTypeMismatchInspection
     */
    public function __construct(string $name, string $identity)
    {
        Assert::notEmpty($name);
        Assert::notEmpty($identity);
        $this->name = mb_strtolower($name);
        $this->identity = mb_strtolower($identity);
    }

    /**
     * Check that the social network is not linked to a person
     * @param Network $network
     * @return bool
     */
    public function isEqualTo(self $network): bool
    {
        return
            $this->getName() === $network->getName() &&
            $this->getIdentity() === $network->getIdentity();
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getIdentity(): string
    {
        return $this->identity;
    }
}
