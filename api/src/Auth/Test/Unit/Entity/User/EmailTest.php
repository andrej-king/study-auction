<?php

declare(strict_types=1);

namespace App\Auth\Test\Unit\Entity\User;

use App\Auth\Entity\User\Email;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * @covers Email
 */
class EmailTest extends TestCase
{
    /**
     * Test email value
     */
    public function testSuccess(): void
    {
        $email = new Email($value = 'email@app.test');

        self::assertEquals($value, $email->getValue());
    }

    /**
     * Test email value
     */
    public function testCase(): void
    {
        $email = new Email('EmAil@app.test');

        self::assertEquals('email@app.test', $email->getValue());
    }

    /**
     * Test create email with incorrect value
     */
    public function testIncorrect(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new Email('not-email');
    }

    /**
     * Test create email with empty value
     */
    public function testEmpty(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new Email('');
    }
}
