<?php

declare(strict_types=1);

namespace App\Auth\Test\Unit\Entity\User;

use App\Auth\Entity\User\Network;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * @covers Network
 */
class NetworkTest extends TestCase
{
    /**
     * Test success create network identity
     */
    public function testSuccess(): void
    {
        $network = new Network($name = 'google', $identity = 'google-1');

        self::assertEquals($name, $network->getName());
        self::assertEquals($identity, $network->getIdentity());
    }

    /**
     * Check behavior with empty identity name
     * @noinspection PhpUnusedLocalVariableInspection
     */
    public function testEmptyName(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new Network($name = '', $identity = 'google-1');
    }

    /**
     * Check behavior with empty identity id
     * @noinspection PhpUnusedLocalVariableInspection
     */
    public function testEmptyIdentity(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new Network($name = 'google', $identity = '');
    }

    /**
     * Check equal method
     * @noinspection PhpUnusedLocalVariableInspection
     */
    public function testEqual(): void
    {
        $network = new Network($name = 'google', $identity = 'google-1');

        self::assertTrue($network->isEqualTo(new Network($name, 'google-1')));
        self::assertFalse($network->isEqualTo(new Network($name, 'google-2')));
        self::assertFalse($network->isEqualTo(new Network('fb', 'fb-1')));
    }
}
