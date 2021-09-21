<?php

declare(strict_types=1);

namespace App\Http\Test\Unit\Middleware;

use App\Http\Middleware\DomainExceptionHandler;
use DomainException;
use PHPUnit\Framework\TestCase;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Psr7\Factory\ResponseFactory;
use Slim\Psr7\Factory\ServerRequestFactory;

/**
 * @covers DomainExceptionHandler
 * @psalm-suppress PropertyNotSetInConstructor
 */
class DomainExceptionHandlerTest extends TestCase
{
    /**
     * Check success work
     */
    public function testNormal(): void
    {
        $middleware = new DomainExceptionHandler();

        $handler = $this->createStub(RequestHandlerInterface::class);
        $handler->method('handle')->willReturn($source = (new ResponseFactory())->createResponse());

        $request = (new ServerRequestFactory())->createServerRequest('POST', 'http://test');
        $response = $middleware->process($request, $handler);

        self::assertEquals($source, $response);
    }

    /**
     * Check if correct return exception
     */
    public function testException(): void
    {
        $middleware = new DomainExceptionHandler();

        $handler = $this->createStub(RequestHandlerInterface::class);
        $handler->method('handle')->willThrowException(new DomainException('Some error.'));

        $request = (new ServerRequestFactory())->createServerRequest('POST', 'http://test');
        $response = $middleware->process($request, $handler);

        self::assertEquals(409, $response->getStatusCode());
        self::assertJson($body = (string)$response->getBody());

        /** @var array $data */
        $data = json_decode($body, true, 512, JSON_THROW_ON_ERROR);

        self::assertEquals([
            'message' => 'Some error.',
        ], $data);
    }
}
