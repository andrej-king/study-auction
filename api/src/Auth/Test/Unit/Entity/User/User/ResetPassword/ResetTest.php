<?php

declare(strict_types=1);

namespace App\Auth\Test\Unit\Entity\User\User\ResetPassword;

use App\Auth\Entity\User\Token;
use App\Auth\Test\Builder\UserBuilder;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

/**
 * @covers User
 */
class ResetTest extends TestCase
{
    /**
     * Test success reset password
     */
    public function testSuccess(): void
    {
        $user = (new UserBuilder())->active()->build();

        $now = new DateTimeImmutable();
        $token = $this->createToken($now->modify('+1 hour'));

        $user->requestPasswordReset($token, $now);

        self::assertNotNull($user->getPasswordResetToken());

        $user->resetPassword($token->getValue(), $now, $hash = 'hash');

        self::assertNull($user->getPasswordResetToken());
        self::assertEquals($hash, $user->getPasswordHash());
    }

    /**
     * Test reset password with invalid token
     */
    public function testInvalidToken(): void
    {
        $user = (new UserBuilder())->active()->build();

        $now = new DateTimeImmutable();
        $token = $this->createToken($now->modify('+1 hour'));

        $user->requestPasswordReset($token, $now);

        $this->expectExceptionMessage('Token is invalid.');
        $user->resetPassword(Uuid::uuid4()->toString(), $now, 'hash');
    }

    /**
     * Test reset password with expired token
     */
    public function testExpiredToken(): void
    {
        $user = (new UserBuilder())->active()->build();

        $now = new DateTimeImmutable();
        $token = $this->createToken($now->modify('+1 hour'));

        $user->requestPasswordReset($token, $now);

        $this->expectExceptionMessage('Token is expired.');
        $user->resetPassword($token->getValue(), $now->modify('+1 day'), 'hash');
    }

    /**
     * Test reset password without reset request before
     */
    public function testNotRequested(): void
    {
        $user = (new UserBuilder())->active()->build();

        $now = new DateTimeImmutable();

        $this->expectExceptionMessage('Resetting is not requested.');
        $user->resetPassword(Uuid::uuid4()->toString(), $now, 'hash');
    }

    /**
     * Create new token
     * @param DateTimeImmutable $date
     * @return Token
     */
    private function createToken(DateTimeImmutable $date): Token
    {
        return new Token(
            Uuid::uuid4()->toString(),
            $date
        );
    }
}
