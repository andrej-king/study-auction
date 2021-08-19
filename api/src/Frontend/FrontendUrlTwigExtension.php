<?php

declare(strict_types=1);

namespace App\Frontend;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class FrontendUrlTwigExtension extends AbstractExtension
{
    private FrontendUrlGenerator $url;

    /**
     * @param FrontendUrlGenerator $url
     */
    public function __construct(FrontendUrlGenerator $url)
    {
        $this->url = $url;
    }


    /**
     * Make custom functions for use is twig file
     *
     * @return TwigFunction[]
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('frontend_url', [$this, 'url']), // call method 'url'
        ];
    }


    /**
     * Url generator
     *
     * @param string $path
     * @param array  $params
     *
     * @return string
     */
    public function url(string $path, array $params = []): string
    {
        return $this->url->generate($path, $params);
    }
}
