<?php

declare(strict_types=1);

namespace App\Auth\Entity\User;

use Webmozart\Assert\Assert;

class Role
{
    public const USER = 'user';
    public const ADMIN = 'admin';

    private string $name;

    /**
     * Role constructor.
     * @param string $name
     */
    public function __construct(string $name)
    {
        Assert::oneOf($name, [
            self::USER,
            self::ADMIN,
        ]);

        $this->name = $name;
    }

    /**
     * @return self
     */
    public static function user(): self
    {
        return new self(self::USER);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}
