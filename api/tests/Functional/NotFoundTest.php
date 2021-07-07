<?php

declare(strict_types=1);

namespace Test\Functional;

use Fig\Http\Message\StatusCodeInterface;

/**
 * @coversNothing
 */
class NotFoundTest extends WebTestCase
{
    public function testNotFound(): void
    {
        $response = $this->app()->handle(self::json('GET', '/not-found'));

        self::assertEquals(StatusCodeInterface::STATUS_NOT_FOUND, $response->getStatusCode());
    }
}
