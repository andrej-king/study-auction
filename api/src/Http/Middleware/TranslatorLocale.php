<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use ptlis\ConNeg\Negotiation;
use Symfony\Component\Translation\Translator;

/**
 * Translator locale settings
 */
class TranslatorLocale implements MiddlewareInterface
{
    private Translator $translator;

    /**
     * @param Translator $translator
     */
    public function __construct(Translator $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @param ServerRequestInterface  $request
     * @param RequestHandlerInterface $handler
     *
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $locale = $request->getHeaderLine('Accept-Language');

        if (!empty($locale)) {
            $this->translator->setLocale($locale);
        }

        return $handler->handle($request);
    }
}
