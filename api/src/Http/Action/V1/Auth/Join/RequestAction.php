<?php

declare(strict_types=1);

namespace App\Http\Action\V1\Auth\Join;

use App\Auth\Command\JoinByEmail\Request\Command;
use App\Auth\Command\JoinByEmail\Request\Handler;
use App\Http\JsonResponse;
use App\Http\EmptyResponse;
use DomainException;
use JsonException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use stdClass;

class RequestAction implements RequestHandlerInterface
{
    private Handler $handler;

    public function __construct(Handler $handler)
    {
        $this->handler = $handler;
    }

    /**
     * Send request for registration
     *
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     * @throws JsonException
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        /**
         * @psalm-var array{email:?string, password:?string} $data
         */
        $data = json_decode((string)$request->getBody(), true);

        $command = new Command();
        $command->email = trim($data['email'] ?? '');
        $command->password = trim($data['password'] ?? '');

        try {
            $this->handler->handle($command);
            return new EmptyResponse(201); // STATUS_CREATED
        } catch (DomainException $exception) {
            return new JsonResponse(['message' => $exception->getMessage()], 409); // STATUS_CONFLICT
        }
    }
}
