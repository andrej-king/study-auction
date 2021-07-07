<?php

declare(strict_types = 1);

namespace Test\Functional;

use Fig\Http\Message\StatusCodeInterface;

class HomeTest extends WebTestCase
{
    public function testSuccess(): void
    {
        $response = $this->app()->handle(self::json('GET', '/'));

        self::assertEquals(StatusCodeInterface::STATUS_OK, $response->getStatusCode());
        self::assertEquals('application/json', $response->getHeaderLine('Content-Type'));
        self::assertEquals('{}', (string)$response->getBody());
    }
}
