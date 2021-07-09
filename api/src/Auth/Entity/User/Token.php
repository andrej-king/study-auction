<?php

declare(strict_types=1);

namespace App\Auth\Entity\User;

use DateTimeImmutable;
use DomainException;
use Webmozart\Assert\Assert;

class Token
{
    private string $value;
    private DateTimeImmutable $expires;

    /**
     * Token constructor.
     * @param string            $value
     * @param DateTimeImmutable $expires
     * @noinspection PhpFieldAssignmentTypeMismatchInspection
     */
    public function __construct(string $value, DateTimeImmutable $expires)
    {
        Assert::uuid($value);
        $this->value = mb_strtolower($value);
        $this->expires = $expires;
    }

    /**
     * Validate token value
     * @param string            $value
     * @param DateTimeImmutable $date
     * @throws DomainException
     */
    public function validate(string $value, DateTimeImmutable $date): void
    {
        if (!$this->isEqualTo($value)) {
            throw new DomainException('Token is invalid.');
        }
        if ($this->isExpiredTo($date)) {
            throw new DomainException('Token is expired.');
        }
    }

    /**
     * Compare token value
     * @param string $value
     * @return bool
     */
    private function isEqualTo(string $value): bool
    {
        return $this->value === $value;
    }

    /**
     * Get token value
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getExpires(): DateTimeImmutable
    {
        return $this->expires;
    }

    /**
     * Check token expired date
     * @param DateTimeImmutable $date
     * @return bool
     */
    public function isExpiredTo(DateTimeImmutable $date): bool
    {
        return $this->expires <= $date;
    }
}
