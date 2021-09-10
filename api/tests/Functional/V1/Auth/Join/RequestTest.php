<?php

declare(strict_types=1);

use Test\Functional\Json;
use Test\Functional\WebTestCase;

/**
 * Tests for join by email request
 */
class RequestTest extends WebTestCase
{
    /**
     * Check method not allowed with GET method
     */
    public function testMethod(): void
    {
        $response = $this->app()->handle(self::json('GET', '/v1/auth/join'));

        self::assertEquals(405, $response->getStatusCode()); // STATUS_METHOD_NOT_ALLOWED
    }

    /**
     * Check answer if success sent method
     */
    public function testSuccess(): void
    {
        $response = $this->app()->handle(self::json('POST', '/v1/auth/join'), [
            'email' => 'new-user@app.test',
            'password' => 'new-password',
        ]);

        self::assertEquals(201, $response->getStatusCode()); // STATUS_CREATED
        self::assertEquals('{}', (string)$response->getBody());
    }

    /**
     * Check response if user already existing
     */
    public function testExisting(): void
    {
        $response = $this->app()->handle(self::json('POST', '/v1/auth/join'), [
            'email' => 'user@app.test',
            'password' => 'new-password',
        ]);

        self::assertEquals(409, $response->getStatusCode()); // STATUS_CONFLICT
        self::assertJson($body = (string)$response->getBody());

        self::assertEquals([
            'message' => 'User already exists.',
        ], Json::decode($body));
    }

    /**
     * Check answer if send request with empty body
     */
    public function testEmpty(): void
    {
        $response = $this->app()->handle(self::json('POST', '/v1/auth/join'));

        self::assertEquals(500, $response->getStatusCode()); // STATUS_INTERNAL_SERVER_ERROR
    }

    public function testNotValid(): void
    {
        $response = $this->app()->handle(self::json('POST', '/v1/auth/join'), [
            'email' => 'not-email',
            'password' => '',
        ]);

        self::assertEquals(500, $response->getStatusCode()); // STATUS_INTERNAL_SERVER_ERROR
    }
}