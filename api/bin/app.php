#!/usr/bin/env php
<?php

declare(strict_types=1);

use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;

require __DIR__ . '/../vendor/autoload.php';

/** @var ContainerInterface $container */
$container = require __DIR__ . '/../config/container.php';

$cli = new Application('Console');

/**
 * @var string[] $commands
 * @psalm-suppress MixedArrayAccess
 */
$commands = $container->get('config')['console']['commands'];
foreach ($commands as $name) {
  /** @var Command $command */
    $command = $container->get($name);
    $cli->add($command);
}
// run console commands with added script in composer.json:
// docker-compose run --rm api-php-cli composer app
// docker-compose run --rm api-php-cli composer app hello

$cli->run();
