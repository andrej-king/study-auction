<?php

declare(strict_types=1);

namespace Test\Functional;

class HomeTest extends WebTestCase
{
    public function testMethod(): void
    {
        $response = $this->app()->handle(self::json('POST', '/'));

        self::assertEquals(405, $response->getStatusCode()); // STATUS_METHOD_NOT_ALLOWED
    }


    public function testSuccess(): void
    {
        $response = $this->app()->handle(self::json('GET', '/'));

        self::assertEquals(200, $response->getStatusCode()); // STATUS_OK
        self::assertEquals('application/json', $response->getHeaderLine('Content-Type'));
        self::assertEquals('{}', (string)$response->getBody());
    }
}
