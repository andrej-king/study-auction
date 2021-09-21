<?php

declare(strict_types=1);

use App\Http\Middleware;
use Slim\App;
use Slim\Middleware\ErrorMiddleware;

/**
 * Connect middlewares with desc order(ErrorMiddleware should be last).
 */
return static function (App $app): void {
    $app->add(Middleware\ClearEmptyInput::class); // Strip whitespace and empty files
    $app->addBodyParsingMiddleware();
    $app->add(ErrorMiddleware::class);
};
