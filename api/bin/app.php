<?php

declare(strict_types=1);

use App\Console\HelloCommand;
use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Application;

require __DIR__ . '/../vendor/autoload.php';

/** @var ContainerInterface $container */
$container = require __DIR__ . '/../config/container.php';

$cli = new Application('Console');

$commands = $container->get('config')['console']['commands'];
foreach ($commands as $command) {
    $cli->add($container->get($command));
}
// show all allow console commands: docker-compose run --rm api-php-cli php bin/app.php
// run special console command: docker-compose run --rm api-php-cli php bin/app.php hello

$cli->run();