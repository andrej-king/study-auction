<?php

declare(strict_types=1);

namespace App\Http\Action;

use App\Http\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use stdClass;

class HomeAction implements RequestHandlerInterface
{
    /**
     * Home action handler
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     * @throws \JsonException
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return new JsonResponse(new stdClass());
    }
}
