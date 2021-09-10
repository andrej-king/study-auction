<?php

declare(strict_types=1);

namespace App\Http;

use Slim\Psr7\Factory\StreamFactory;
use Slim\Psr7\Response;

class EmptyResponse extends Response
{

    /**
     * EmptyResponse constructor.
     *
     * @param int $status default code STATUS_NO_CONTENT
     */
    public function __construct(int $status = 204)
    {
        // rb - read only, force enable binary mode
        parent::__construct(
            $status,
            null,
            (new StreamFactory())->createStreamFromResource(fopen('php://temp', 'rb'))
        );
    }
}
