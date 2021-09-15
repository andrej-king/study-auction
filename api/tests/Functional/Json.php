<?php

declare(strict_types=1);

namespace Test\Functional;

use JsonException;

/**
 * Test helper
 */
class Json
{
    /**
     * Decode JSON in array
     *
     * @param string $data
     *
     * @return array
     * @throws JsonException
     */
    public static function decode(string $data): array
    {
        /** @var array */
        return json_decode($data, true, 512, JSON_THROW_ON_ERROR);
    }
}
