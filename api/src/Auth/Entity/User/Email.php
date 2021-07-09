<?php

declare(strict_types=1);

namespace App\Auth\Entity\User;

use InvalidArgumentException;
use Webmozart\Assert\Assert;

class Email
{
    private string $value;

    /**
     * Email constructor.
     * @param string $value
     * @throws InvalidArgumentException
     * @noinspection PhpFieldAssignmentTypeMismatchInspection
     */
    public function __construct(string $value)
    {
        Assert::notEmpty($value);
        Assert::email($value);
        $this->value = mb_strtolower($value);
    }

    /**
     * Get email value
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }
}
