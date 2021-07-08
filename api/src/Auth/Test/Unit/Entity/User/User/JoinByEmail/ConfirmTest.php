<?php

declare(strict_types=1);

namespace App\Auth\Test\Unit\Entity\User\User\JoinByEmail;

use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

/**
 * @covers User
 */
class ConfirmTest extends TestCase
{
    public function testSuccess(): void
    {
        $user = (new UserBuilder())
            ->withJoinConfirmToken($token = $this->createToken())
            ->build();

        self::assertTrue($user->isWait());
        self::assertFalse($user->isActive());

        $user->confirmJoin(
            $token->getValue(),
            $token->getExpires()->modify('-1 day')
        );

        self::assertFalse($user->isWait());
        self::assertTrue($user->isActive());

        self::assertNull($user->getJoinConfirmToken());
    }

    public function testWrong(): void
    {
        $user = (new UserBuilder())
            ->withJoinConfirmToken($token = $this->createToken())
            ->build();

        $this->expectExceptionMessage('Token is invalid');

        $user->confirmJoin(
            Uuid::uuid4()->toString(),
            $token->getExpires()->modify('-1 day')
        );
    }
}
