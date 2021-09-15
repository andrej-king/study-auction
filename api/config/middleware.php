<?php

declare(strict_types=1);

use App\Http\Middleware;
use Slim\App;
use Slim\Middleware\ErrorMiddleware;

return static function (App $app): void {
    // connect middlewares with desc order (ErrorMiddleware should be last)
    $app->add(Middleware\ClearEmptyInput::class);
    $app->addBodyParsingMiddleware();
    $app->add(ErrorMiddleware::class);
};
