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

        self::assertEquals(StatusCodeInterface::STATUS_NOT_FOUND, $response->getStatusCode());
        self::assertJson($body = (string)$response->getBody());

        /** @var array $data */
        $data = json_decode($body, true, 512, JSON_THROW_ON_ERROR);

        // check in sub array
        self::assertArraySubset([
            'message' => '404 Not Found',
        ], $data);
    }
}
