<?php

declare(strict_types=1);

namespace Test\Functional;

use DMS\PHPUnitExtensions\ArraySubset\ArraySubsetAsserts;

class NotFoundTest extends WebTestCase
{
    use ArraySubsetAsserts;

    /**
     * Check answer from non-existent page.
     */
    public function testNotFound(): void
    {
        $response = $this->app()->handle(self::json('GET', '/not-found'));

        self::assertEquals(404, $response->getStatusCode()); // STATUS_NOT_FOUND
        self::assertJson($body = (string)$response->getBody());

        $data = Json::decode($body);

        // check in sub array
        self::assertArraySubset([
            'message' => '404 Not Found',
        ], $data);
    }
}
