<?php

namespace Test\Unit\Http;

use App\Http\JsonResponse;
use Fig\Http\Message\StatusCodeInterface;
use PHPUnit\Framework\TestCase;
use stdClass;

class JsonResponseTest extends TestCase
{
    public function testIntWithCode(): void
    {
        $response = new JsonResponse(0, StatusCodeInterface::STATUS_CREATED);

        $this->assertEquals('application/json', $response->getHeaderLine('Content-Type'));
        $this->assertEquals('0', $response->getBody()->getContents());
        $this->assertEquals(StatusCodeInterface::STATUS_CREATED, $response->getStatusCode());
    }

    public function testNull(): void
    {
        $response = new JsonResponse(null);

        $this->assertEquals('null', $response->getBody()->getContents());
        $this->assertEquals(StatusCodeInterface::STATUS_OK, $response->getStatusCode());
    }

    public function testInt(): void
    {
        $response = new JsonResponse(12);

        $this->assertEquals('12', $response->getBody()->getContents());
        $this->assertEquals(StatusCodeInterface::STATUS_OK, $response->getStatusCode());
    }

    public function testString(): void
    {
        $response = new JsonResponse('12');

        $this->assertEquals('"12"', $response->getBody()->getContents());
        $this->assertEquals(StatusCodeInterface::STATUS_OK, $response->getStatusCode());
    }

    public function testObject(): void
    {
        $object = new stdClass();
        $object->str = 'value';
        $object->int = 1;
        $object->none = null;

        $response = new JsonResponse($object);

        $this->assertEquals('{"str":"value","int":1,"none":null}', $response->getBody()->getContents());
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testArray(): void
    {
        $array = ['str' => 'value', 'int' => 1, 'none' => null];

        $response = new JsonResponse($array);

        $this->assertEquals('{"str":"value","int":1,"none":null}', $response->getBody()->getContents());
        $this->assertEquals(200, $response->getStatusCode());
    }
}
