<?php

declare(strict_types=1);

namespace Test\Functional\V1\Auth\Join;

use Ramsey\Uuid\Uuid;
use Test\Functional\Json;
use Test\Functional\WebTestCase;

/**
 * Tests for confirm join by email request
 */
class ConfirmTest extends WebTestCase
{
    /**
     * Automatic call this before each test
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->loadFixtures([
            ConfirmFixture::class,
        ]);
    }

    /**
     * Check answer if send not allowed method
     */
    public function testMethod(): void
    {
        $response = $this->app()->handle(self::json('GET', '/v1/auth/join/confirm'));

        self::assertEquals(405, $response->getStatusCode()); // STATUS_METHOD_NOT_ALLOWED
    }

    /**
     * Check answer if send valid token
     */
    public function testSuccess(): void
    {
        $response = $this->app()->handle(self::json('POST', '/v1/auth/join/confirm', [
            'token' => ConfirmFixture::VALID,
        ]));

        self::assertEquals(200, $response->getStatusCode());
        self::assertEquals('', (string)$response->getBody());
    }

    /**
     * Check answer if success sent invalid token
     */
    public function testExpired(): void
    {
        $response = $this->app()->handle(self::json('POST', '/v1/auth/join/confirm', [
            'token' => ConfirmFixture::EXPIRED
        ]));

        self::assertEquals(409, $response->getStatusCode()); // STATUS_METHOD_NOT_ALLOWED
        self::assertJson($body = (string)$response->getBody());

        self::assertEquals([
            'message' => 'Token is expired.'
        ], Json::decode($body));
    }

    /**
     * Check answer if send request without token value
     */
    public function testEmpty(): void
    {
        $response = $this->app()->handle(self::json('POST', '/v1/auth/join/confirm', []));

        self::assertEquals(422, $response->getStatusCode()); // STATUS_UNPROCESSABLE_ENTITY
        self::assertJson($body = (string)$response->getBody());

        self::assertEquals([
            'errors' => [
                'token' => 'This value should not be blank.',
            ],
        ], Json::decode($body));
    }

    /**
     * Check answer if send request with no existing token value
     */
    public function testNoExisting(): void
    {
        $response = $this->app()->handle(self::json('POST', '/v1/auth/join/confirm', [
            'token' => Uuid::uuid4()->toString(),
        ]));

        self::assertEquals(409, $response->getStatusCode());
        self::assertJson($body = (string)$response->getBody());

        self::assertEquals([
            'message' => 'Incorrect token.',
        ], Json::decode($body));
    }
}
