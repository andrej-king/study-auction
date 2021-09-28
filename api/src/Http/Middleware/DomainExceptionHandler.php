<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Http\JsonResponse;
use DomainException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Filter Domain Exceptions
 */
class DomainExceptionHandler implements MiddlewareInterface
{
    private LoggerInterface $logger;
    private TranslatorInterface $translator;

    /**
     * @param LoggerInterface $logger for recording logs, if code get an exception
     */
    public function __construct(LoggerInterface $logger, TranslatorInterface $translator)
    {
        $this->logger = $logger;
        $this->translator = $translator;
    }

    /**
     * @throws \JsonException
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            return $handler->handle($request);
        } catch (DomainException $exception) {
            $this->logger->warning($exception->getMessage(), [
                'exception' => $exception,
                'url' => (string)$request->getUri(),
            ]);

            return new JsonResponse([
                'message' => $this->translator->trans($exception->getMessage(), [], 'exceptions'),
            ], 409); // STATUS_CONFLICT
        }
    }
}
