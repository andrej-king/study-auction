<?php

declare(strict_types=1);

use App\Http;
use DI\ContainerBuilder;
use Psr\Http\Message\ResponseFactoryInterface;
use Slim\Factory\AppFactory;
use Slim\Psr7\Factory\ResponseFactory;

http_response_code(500); // default status

require __DIR__ . '/../vendor/autoload.php';

$builder = new ContainerBuilder();

$builder->addDefinitions([
   'config' => [
       'debug' => (bool)getenv('APP_DEBUG')
   ],
    ResponseFactoryInterface::class => Di\get(ResponseFactory::class)
]);

$container = $builder->build();

$app = AppFactory::createFromContainer($container);

$app->addErrorMiddleware($container->get('config')['debug'], true, true);

$app->get('/', Http\Action\HomeAction::class);

$app->run();