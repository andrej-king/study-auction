<?php

declare(strict_types=1);

namespace App\Http\Action\V1\Auth\Join;

use App\Auth\Command\JoinByEmail\Request\Command;
use App\Auth\Command\JoinByEmail\Request\Handler;
use App\Http\EmptyResponse;
use App\Http\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Handler for join by email request
 */
class RequestAction implements RequestHandlerInterface
{
    private Handler $handler;
    private ValidatorInterface $validator;

    public function __construct(Handler $handler, ValidatorInterface $validator)
    {
        $this->handler = $handler;
        $this->validator = $validator;
    }

    /**
     * Send request for registration
     *
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     * @throws \JsonException
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        /**
         * @psalm-var array{'email':?string,'password':?string} $data
         */
        $data = $request->getParsedBody();

        $command = new Command();
        $command->email = $data['email'] ?? '';
        $command->password = $data['password'] ?? '';

        $violations = $this->validator->validate($command); // validation with symfony component.

        if ($violations->count() > 0) {
            $errors = [];

            /** @var ConstraintViolationInterface $violation */
            foreach ($violations as $violation) {
                $errors[$violation->getPropertyPath()] = $violation->getMessage();
            }
            return new JsonResponse(['errors' => $errors], 422);
        }

        // try-catch for DomainException moved in middleware
        $this->handler->handle($command);
        return new EmptyResponse(201); // STATUS_CREATED
    }
}
