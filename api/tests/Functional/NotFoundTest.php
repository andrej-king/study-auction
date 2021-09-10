<?php

declare(strict_types=1);

namespace Test\Functional;

use DMS\PHPUnitExtensions\ArraySubset\ArraySubsetAsserts;
use Fig\Http\Message\StatusCodeInterface;

class NotFoundTest extends WebTestCase
{
    use ArraySubsetAsserts;

    public function testNotFound(): void
    {
        $response = $this->app()->handle(self::json('GET', '/not-found'));

        self::assertEquals(StatusCodeInterface::STATUS_NOT_FOUND, $response->getStatusCode()); // 404
        self::assertJson($body = (string)$response->getBody());

        $data = Json::decode($body);

        // check in sub array
        self::assertArraySubset([
            'message' => '404 Not Found',
        ], $data);
    }
}
