<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use ptlis\ConNeg\Negotiation;

/**
 * Translator Locale settings
 */
class LocaleNegotiation implements MiddlewareInterface
{
    private array $locales;

    /**
     * @param array $locales
     */
    public function __construct(array $locales)
    {
        $this->locales = $locales;
    }

    /**
     * @param ServerRequestInterface  $request
     * @param RequestHandlerInterface $handler
     *
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $locale = self::parseLocale(
            $request->getHeaderLine('Accept-Language'),
            $this->locales
        );

        if (empty($locale)) {
            return $handler->handle($request);
        }

        return $handler->handle($request->withHeader('Accept-Language', $locale));
    }

    /**
     * Parse locale from allowed in settings.
     *
     * @param string|null $accept
     * @param array       $allowed
     *
     * @return string|null
     */
    private static function parseLocale(?string $accept, array $allowed): ?string
    {
        /** @var ?string $default */
        $default = $allowed[0] ?? null;

        if (empty($accept)) {
            return $default;
        }

        $negotiation = new Negotiation();
        $locale = $negotiation->languageBest($accept, implode(',', $allowed));

        return in_array($locale, $allowed, true) ? $locale : $default;
    }
}
