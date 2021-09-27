<?php

declare(strict_types=1);

namespace Test\Functional\V1\Auth\Join;

use Test\Functional\Json;
use Test\Functional\WebTestCase;

/**
 * Tests for join by email request
 */
class RequestTest extends WebTestCase
{
    /**
     * Automatic call this before each test
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->loadFixtures([
            RequestFixture::class,
        ]);
    }

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
        $this->mailer()->clear();

        $newEmail = 'new-user@app.test';

        $response = $this->app()->handle(self::json('POST', '/v1/auth/join', [
            'email' => $newEmail,
            'password' => 'new-password',
        ]));

        self::assertEquals(201, $response->getStatusCode()); // STATUS_CREATED
        self::assertEquals('', (string)$response->getBody());

        self::assertTrue($this->mailer()->hasEmailSentTo($newEmail));
    }

    /**
     * Check response if user already existing
     */
    public function testExisting(): void
    {
        $response = $this->app()->handle(self::json('POST', '/v1/auth/join', [
            'email' => 'existing@app.test',
            'password' => 'new-password',
        ]));

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

    /**
     * Check answer, if send email with invalid form
     */
    public function testNotValid(): void
    {
        $response = $this->app()->handle(self::json('POST', '/v1/auth/join', [
            'email' => 'not-email',
            'password' => '',
        ]));

        self::assertEquals(500, $response->getStatusCode()); // STATUS_INTERNAL_SERVER_ERROR
    }
}
