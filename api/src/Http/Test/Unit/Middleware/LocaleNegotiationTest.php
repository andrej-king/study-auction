<?php

declare(strict_types=1);

namespace App\Http\Test\Unit\Middleware;

use App\Http\Middleware\LocaleNegotiation;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Psr7\Factory\ResponseFactory;
use Slim\Psr7\Factory\ServerRequestFactory;

/**
 * @covers LocaleNegotiation
 */
class LocaleNegotiationTest extends TestCase
{
    /**
     * Check default value.
     */
    public function testDefault(): void
    {
        $middleware = new LocaleNegotiation(['en', 'ru']);

        $source = (new ResponseFactory())->createResponse();

        $handler = $this->createStub(RequestHandlerInterface::class);
        $handler->method('handle')->willReturnCallback(
            static function (ServerRequestInterface $request) use ($source): ResponseInterface {
                self::assertEquals('en', $request->getHeaderLine('Accept-Language'));
                return $source;
            }
        );

        $response = $middleware->process(self::createRequest(), $handler);

        self::assertEquals($source, $response);
    }

    /**
     * Check response if send request with valid locale language.
     */
    public function testAccepted(): void
    {
        $middleware = new LocaleNegotiation(['en', 'ru']);

        $source = (new ResponseFactory())->createResponse();

        $handler = $this->createStub(RequestHandlerInterface::class);
        $handler->method('handle')->willReturnCallback(
            static function (ServerRequestInterface $request) use ($source): ResponseInterface {
                self::assertEquals('ru', $request->getHeaderLine('Accept-Language'));
                return $source;
            }
        );

        $request = self::createRequest()->withHeader('Accept-Language', 'ru');

        $middleware->process($request, $handler);
    }

    /**
     * Check response if send request with multi languages.
     */
    public function testMulti(): void
    {
        $middleware = new LocaleNegotiation(['en', 'fr', 'ru']);

        $source = (new ResponseFactory())->createResponse();

        $handler = $this->createStub(RequestHandlerInterface::class);
        $handler->method('handle')->willReturnCallback(
            static function (ServerRequestInterface $request) use ($source): ResponseInterface {
                self::assertEquals('ru', $request->getHeaderLine('Accept-Language'));
                return $source;
            }
        );

        $request = self::createRequest()->withHeader('Accept-Language', 'es;q=0.9, ru;q=0.8, *;q=0.5');

        $middleware->process($request, $handler);
    }

    /**
     * Check response if send request with invalid lang.
     */
    public function testOther(): void
    {
        $middleware = new LocaleNegotiation(['en', 'ru']);

        $source = (new ResponseFactory())->createResponse();

        $handler = $this->createStub(RequestHandlerInterface::class);
        $handler->method('handle')->willReturnCallback(
            static function (ServerRequestInterface $request) use ($source): ResponseInterface {
                self::assertEquals('en', $request->getHeaderLine('Accept-Language'));
                return $source;
            }
        );

        $request = self::createRequest()->withHeader('Accept-Language', 'es');

        $middleware->process($request, $handler);
    }

    /**
     * Create request.
     *
     * @return ServerRequestInterface
     */
    private static function createRequest(): ServerRequestInterface
    {
        return (new ServerRequestFactory())->createServerRequest('POST', 'http://test');
    }
}
