<?php

declare(strict_types=1);

namespace App\Auth\Test\Unit\Entity\User;

use App\Auth\Entity\User\NetworkIdentity;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * @covers NetworkIdentity
 */
class NetworkIdentityTest extends TestCase
{
    /**
     * Test success create network identity
     */
    public function testSuccess(): void
    {
        $network = new NetworkIdentity($name = 'google', $identity = 'google-1');

        self::assertEquals($name, $network->getNetwork());
        self::assertEquals($identity, $network->getIdentity());
    }

    /**
     * Check behavior with empty identity name
     */
    public function testEmptyName(): void
    {
        $this->expectException(InvalidArgumentException::class);
        /** @noinspection PhpUnusedLocalVariableInspection */
        new NetworkIdentity($name = '', $identity = 'google-1');
    }

    /**
     * Check behavior with empty identity id
     */
    public function testEmptyIdentity(): void
    {
        $this->expectException(InvalidArgumentException::class);
        /** @noinspection PhpUnusedLocalVariableInspection */
        new NetworkIdentity($name = 'google', $identity = '');
    }

    /**
     * Check equal method
     */
    public function testEqual(): void
    {
        /** @noinspection PhpUnusedLocalVariableInspection */
        $network = new NetworkIdentity($name = 'google', $identity = 'google-1');

        self::assertTrue($network->isEqualTo(new NetworkIdentity($name, 'google-1')));
        self::assertFalse($network->isEqualTo(new NetworkIdentity($name, 'google-2')));
        self::assertFalse($network->isEqualTo(new NetworkIdentity('fb', 'fb-1')));
    }
}
