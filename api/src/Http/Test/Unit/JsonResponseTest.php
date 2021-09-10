<?php

namespace App\Http\Test\Unit;

use App\Http\JsonResponse;
use Fig\Http\Message\StatusCodeInterface;
use JsonException;
use PHPUnit\Framework\TestCase;
use stdClass;

class JsonResponseTest extends TestCase
{
    /**
     * Check create status code
     * @throws JsonException
     */
    public function testIntWithCode(): void
    {
        $response = new JsonResponse(0, StatusCodeInterface::STATUS_CREATED); // 201

        self::assertEquals('application/json', $response->getHeaderLine('Content-Type'));
        self::assertEquals('0', $response->getBody()->getContents());
        self::assertEquals(StatusCodeInterface::STATUS_CREATED, $response->getStatusCode());
    }

    /**
     * @dataProvider getCases
     * @param mixed $source
     * @param mixed $expect
     * @throws JsonException
     */
    public function testResponse($source, $expect): void
    {
        $response = new JsonResponse($source);

        self::assertEquals('application/json', $response->getHeaderLine('Content-Type'));
        self::assertEquals($expect, $response->getBody()->getContents());
        self::assertEquals(StatusCodeInterface::STATUS_OK, $response->getStatusCode()); // 200
    }


    /**
     * @return array<mixed>
     */
    public function getCases(): array
    {
        $object = new stdClass();
        $object->str = 'value';
        $object->int = 1;
        $object->none = null;

        $array = [
            'str'  => 'value',
            'int'  => 1,
            'none' => null
        ];

        return [
            'null'   => [null, 'null'],
            'empty'  => ['', '""'],
            'number' => [12, '12'],
            'string' => ['12', '"12"'],
            'object' => [$object, '{"str":"value","int":1,"none":null}'],
            'array'  => [$array, '{"str":"value","int":1,"none":null}'],
        ];
    }
}
