<?php

declare(strict_types=1);

namespace App\Auth\Entity\User;

use Webmozart\Assert\Assert;

class Status
{
    public const WAIT   = 'wait';
    public const ACTIVE = 'active';

    private string $name;

    /**
     * Status constructor.
     * @param string $name
     */
    public function __construct(string $name)
    {
        Assert::oneOf($name, [
            self::WAIT,
            self::ACTIVE
        ]);

        $this->name = $name;
    }

    /**
     * @return self
     */
    public static function wait(): self
    {
        return new self(self::WAIT);
    }

    /**
     * @return self
     */
    public static function active(): self
    {
        return new self(self::ACTIVE);
    }

    /**
     * @return bool
     */
    public function isWait(): bool
    {
        return $this->name === self::WAIT;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->name === self::ACTIVE;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
