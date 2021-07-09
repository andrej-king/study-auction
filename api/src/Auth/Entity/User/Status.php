<?php

declare(strict_types=1);

namespace App\Auth\Entity\User;

class Status
{
    private const WAIT   = 'wait';
    private const ACTIVE = 'active';

    private string $name;

    /**
     * Status constructor.
     * @param string $name
     */
    private function __construct(string $name)
    {
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
}
