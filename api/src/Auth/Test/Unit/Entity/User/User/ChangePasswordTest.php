<?php

declare(strict_types=1);

namespace App\Auth\Test\Unit\Entity\User\User;

use App\Auth\Service\PasswordHasher;
use App\Auth\Test\Builder\UserBuilder;
use PHPUnit\Framework\TestCase;

/**
 * @covers User
 */
class ChangePasswordTest extends TestCase
{
    /**
     * Chech success change password
     */
    public function testSuccess(): void
    {
        $user = (new UserBuilder())
            ->active()
            ->build();

        $hasher = $this->createHasher(true, $hash = 'new-hash');

        $user->changePassword(
            'old-password',
            'new-password',
            $hasher
        );

        self::assertEquals($hash, $user->getPasswordHash());
    }

    /**
     * Test with wrong current password
     */
    public function testWrongCurrent(): void
    {
        $user = (new UserBuilder())
            ->active()
            ->build();

        $hasher = $this->createHasher(false, 'new-hash');

        $this->expectExceptionMessage('Incorrect current password.');
        $user->changePassword(
            'wrong-old-password',
            'new-password',
            $hasher
        );
    }

    /**
     * Test by network (without password)
     */
    public function testByNetwork(): void
    {
        $user = (new UserBuilder())
            ->viaNetwork()
            ->build();

        $hasher = $this->createHasher(false, 'new-hash');

        $this->expectExceptionMessage('User does not have an old password.');
        $user->changePassword(
            'any-old-password',
            'new-password',
            $hasher
        );
    }

    /**
     * Mock for password hasher
     * @param bool   $valid
     * @param string $hash
     * @return PasswordHasher
     */
    private function createHasher(bool $valid, string $hash): PasswordHasher
    {
        $hasher = $this->createStub(PasswordHasher::class);
        $hasher->method('validate')->willReturn($valid);
        $hasher->method('hash')->willReturn($hash);
        return $hasher;
    }
}
