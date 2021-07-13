<?php

declare(strict_types=1);

namespace App\Auth\Test\Unit\Entity\User\User;

use App\Auth\Test\Builder\UserBuilder;
use PHPUnit\Framework\TestCase;

/**
 * @covers User::remove
 */
class RemoveTest extends TestCase
{
    /**
     * Check no errors
     * @doesNotPerformAssertions
     */
    public function testSuccess(): void
    {
        $user = (new UserBuilder())
            ->build();

        $user->remove();
    }

    /**
     * Test unable to remove active user
     */
    public function testActive(): void
    {
        $user = (new UserBuilder())
            ->active()
            ->build();

        $this->expectExceptionMessage('Unable to remove active user.');
        $user->remove();
    }
}
