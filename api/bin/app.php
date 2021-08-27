#!/usr/bin/env php
<?php

declare(strict_types=1);

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper;
use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;

require __DIR__ . '/../vendor/autoload.php';

/** Init sentry for send errors in external logger panel */
if (getenv('SENTRY_DSN')) {
    Sentry\init(['dsn' => getenv('SENTRY_DSN')]);
}

/** @var ContainerInterface $container */
$container = require __DIR__ . '/../config/container.php';

$cli = new Application('Console');

/** Turn off catch exceptions from Symfony and send it upper */
if (getenv('SENTRY_DSN')) {
    $cli->setCatchExceptions(false);
}

/**
 * @var string[] $commands
 * @psalm-suppress MixedArrayAccess
 */
$commands = $container->get('config')['console']['commands'];

/** @var EntityManagerInterface $entityManager*/
$entityManager = $container->get(EntityManagerInterface::class);

$cli->getHelperSet()->set(new EntityManagerHelper($entityManager), 'em');

foreach ($commands as $name) {
  /** @var Command $command */
    $command = $container->get($name);
    $cli->add($command);
}
// run console commands with added script in composer.json:
// docker-compose run --rm api-php-cli composer app
// docker-compose run --rm api-php-cli composer app hello
// docker-compose run --rm api-php-cli composer app migrations:diff // generate migration
// docker-compose run --rm api-php-cli composer app migrations:migrate // set migrations

$cli->run();
