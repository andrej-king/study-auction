<?php

declare(strict_types=1);

namespace App\Http\Action\V1\Auth\Join;

use App\Auth\Command\JoinByEmail\Confirm\Command;
use App\Auth\Command\JoinByEmail\Confirm\Handler;
use App\Http\EmptyResponse;
use App\Http\Validator\Validator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Class for work with confirm action in "join by email confirm"
 */
class ConfirmAction implements RequestHandlerInterface
{
    private Handler $handler;
    private Validator $validator;

    /**
     * @param Handler   $handler
     * @param Validator $validator
     */
    public function __construct(Handler $handler, Validator $validator)
    {
        $this->handler = $handler;
        $this->validator = $validator;
    }

    /**
     * Handler for join by email confirm
     *
     * @param ServerRequestInterface $request
     *
     * @return ResponseInterface
     * @throws \JsonException
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        /**
         * @psalm-var array{'token':'?string'} $data
         */
        $data = $request->getParsedBody();

        $command = new Command();
        $command->token = $data['token'] ?? '';

        $this->validator->validate($command); // try-catch for ValidatorException moved in middleware
        $this->handler->handle($command); // try-catch for DomainException moved in middleware

        return new EmptyResponse(200);
    }
}
