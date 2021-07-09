<?php

declare(strict_types=1);

namespace App\Auth\Test\Unit\Service;

use App\Auth\Service\PasswordHasher;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * @covers PasswordHasher
 */
class PasswordHasherTest extends TestCase
{
    /**
     * Test PasswordHasher result
     */
    public function testHash(): void
    {
        $hasher = new PasswordHasher(16);

        $hashe = $hasher->hash($password = 'new-password');

        self::assertNotEmpty($hashe);
        self::assertNotEquals($password, $hashe);
    }

    /**
     * Test behavior if try create PasswordHasher with empty hash value
     */
    public function testHashEmpty(): void
    {
        $hasher = new PasswordHasher(16);

        $this->expectException(InvalidArgumentException::class);
        $hasher->hash('');
    }

    /**
     * Test PasswordHasher validator
     */
    public function testValidate(): void
    {
        $hasher = new PasswordHasher(16);

        $hash = $hasher->hash($password = 'new-password');

        self::assertTrue($hasher->validate($password, $hash));
        self::assertFalse($hasher->validate('wrong-password', $hash));
    }
}
