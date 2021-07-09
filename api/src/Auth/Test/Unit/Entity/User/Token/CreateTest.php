<?php

declare(strict_types=1);

namespace App\Auth\Test\Unit\Entity\User\Token;

use App\Auth\Entity\User\Token;
use DateTimeImmutable;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

/**
 * @covers Token
 */
class CreateTest extends TestCase
{
    /**
     * Check success create Token
     */
    public function testSuccess(): void
    {
        $token = new Token(
            $value = Uuid::uuid4()->toString(),
            $expires = new DateTimeImmutable()
        );

        self::assertEquals($value, $token->getValue());
        self::assertEquals($expires, $token->getExpires());
    }

    /**
     * Check token value
     */
    public function testCase(): void
    {
        $value = Uuid::uuid4()->toString();

        $token = new Token(mb_strtoupper($value), new DateTimeImmutable());

        self::assertEquals($value, $token->getValue());
    }

    /**
     * Check behavior with incorrect token
     */
    public function testIncorrect(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new Token('12345', new DateTimeImmutable());
    }

    /**
     * Check behavior with empty token
     */
    public function testEmpty(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new Token('', new DateTimeImmutable());
    }
}
