<?php

declare(strict_types=1);

namespace App\Auth\Test\Unit\Service;

use App\Auth\Service\Tokenizer;
use DateInterval;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

/**
 * @covers Tokenizer
 */
class TokenizerTest extends TestCase
{
    /**
     * Tokenizer test
     */
    public function testSuccess(): void
    {
        $interval = new DateInterval('PT1H'); // PT1H = plus time 1 hour
        $date = new DateTimeImmutable('+1 day');

        $tokenizer = new Tokenizer($interval);

        $token = $tokenizer->generate($date);

        self::assertEquals($date->add($interval), $token->getExpires());
    }
}
