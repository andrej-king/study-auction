<?php

declare(strict_types=1);

namespace App\Auth\Service;

use App\Auth\Entity\User\Token;
use DateInterval;
use DateTimeImmutable;
use Ramsey\Uuid\Uuid;

class Tokenizer
{
    private DateInterval $interval;

    /**
     * Tokenizer constructor.
     * @param DateInterval $interval
     */
    public function __construct(DateInterval $interval)
    {
        $this->interval = $interval;
    }

    /**
     * Token generator
     * @param DateTimeImmutable $date
     * @return Token
     */
    public function generate(DateTimeImmutable $date): Token
    {
        return new Token(
            Uuid::uuid4()->toString(),
            $date->add($this->interval)
        );
    }
}
