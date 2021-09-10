<?php

declare(strict_types=1);

namespace App\Http\Test\Unit;

use App\Http\EmptyResponse;
use PHPUnit\Framework\TestCase;

class EmptyResponseTest extends TestCase
{
    /**
     * Check response if create default object
     */
    public function testDefault(): void
    {
        $response = new EmptyResponse();

        self::assertEquals(204, $response->getStatusCode()); // STATUS_NO_CONTENT
        self::assertFalse($response->hasHeader('Content-Type'));

        self::assertEquals('', (string)$response->getBody());
        self::assertFalse($response->getBody()->isWritable());
    }

    public function testWithCode(): void
    {
        $response = new EmptyResponse(201); // STATUS_CREATED

        self::assertEquals(201, $response->getStatusCode());
    }
}
