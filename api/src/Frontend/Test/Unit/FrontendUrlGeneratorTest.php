<?php

declare(strict_types=1);

namespace App\Frontend\Test\Unit;

use App\Frontend\FrontendUrlGenerator;
use PHPUnit\Framework\TestCase;

/**
 * @covers FrontendUrlGenerator
 */
class FrontendUrlGeneratorTest extends TestCase
{
    /**
     * Check url without params
     */
    public function testEmpty(): void
    {
        $url = 'http://test';
        $generator = new FrontendUrlGenerator($url);

        self::assertEquals($url, $generator->generate(''));
    }

    /**
     * Check url with path
     */
    public function testPath(): void
    {
        $url = 'http://test';
        $path = 'path';
        $generator = new FrontendUrlGenerator($url);

        self::assertEquals($url . '/' . $path, $generator->generate($path));
    }

    public function testParams(): void
    {
        $url = 'http://test';
        $path = 'path';
        $generator = new FrontendUrlGenerator($url);

        self::assertEquals($url . '/' . $path . '?a=1&b=2', $generator->generate($path, [
            'a' => '1',
            'b' => 2,
        ]));
    }
}
