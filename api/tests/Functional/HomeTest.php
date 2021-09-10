<?php

declare(strict_types=1);

namespace Test\Functional;

use Fig\Http\Message\StatusCodeInterface;

class HomeTest extends WebTestCase
{
    public function testMethod(): void
    {
        $response = $this->app()->handle(self::json('POST', '/'));

        self::assertEquals(StatusCodeInterface::STATUS_METHOD_NOT_ALLOWED, $response->getStatusCode()); // 405
    }


    public function testSuccess(): void
    {
        $response = $this->app()->handle(self::json('GET', '/'));

        self::assertEquals(StatusCodeInterface::STATUS_OK, $response->getStatusCode()); // 200
        self::assertEquals('application/json', $response->getHeaderLine('Content-Type'));
        self::assertEquals('{}', (string)$response->getBody());
    }
}
