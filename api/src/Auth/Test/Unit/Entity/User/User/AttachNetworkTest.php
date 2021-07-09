<?php

declare(strict_types=1);

namespace App\Auth\Test\Unit\Entity\User\User;

use App\Auth\Entity\User\NetworkIdentity;
use App\Auth\Test\Builder\UserBuilder;
use PHPUnit\Framework\TestCase;

/**
 * @covers User
 */
class AttachNetworkTest extends TestCase
{
    /**
     * Test success attach social network to user
     */
    public function testSuccess(): void
    {
        $user = (new UserBuilder())
            ->active()
            ->build();

        $network = new NetworkIdentity('fb', '0000001');
        $user->attachNetwork($network);

        self::assertCount(1, $networks = $user->getNetworks());
        self::assertEquals($network, $networks[0] ?? null);
    }

    /**
     * Check behavior if social network already attached to user id
     */
    public function testAlready(): void
    {
        $user = (new UserBuilder())
            ->active()
            ->build();

        $network = new NetworkIdentity('fb', '0000001');

        $user->attachNetwork($network);

        $this->expectExceptionMessage('Network is already attached.');
        $user->attachNetwork($network);
    }
}
