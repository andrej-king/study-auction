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

    /**
     * @dataProvider getCases
     * @param mixed $source
     * @param mixed $expect
     */
    public function testResponse($source, $expect): void
    {
        $response = new JsonResponse($source);

        $this->assertEquals('application/json', $response->getHeaderLine('Content-Type'));
        $this->assertEquals($expect, $response->getBody()->getContents());
        $this->assertEquals(StatusCodeInterface::STATUS_OK, $response->getStatusCode());
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
