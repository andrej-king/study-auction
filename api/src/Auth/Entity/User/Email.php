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

    /**
     * Compare two emails
     * @param self $other
     * @return bool
     */
    public function isEqualTo(self $other): bool
    {
        return $this->value === $other->value;
    }
}
