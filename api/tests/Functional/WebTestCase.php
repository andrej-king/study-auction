<?php

declare(strict_types=1);

namespace Test\Functional;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\App;
use Slim\Psr7\Factory\ServerRequestFactory;

/**
 * Require "StatusCodeInterface" from package "fig/http-message-util" for check response status
 */
class WebTestCase extends TestCase
{
    // for caching app and don't require each time
    private ?App $app = null;

    // will call it after each test call
    protected function tearDown(): void
    {
        $this->app = null;
        parent::tearDown();
    }

    protected static function json(string $method, string $path, array $body = []): ServerRequestInterface
    {
        $request = self::request($method, $path)
            ->withHeader('Accept', 'application/json')
            ->withHeader('Content-Type', 'application/json');
        $request->getBody()->write(json_encode($body, JSON_THROW_ON_ERROR));
        return $request;
    }

    protected static function request(string $method, string $path): ServerRequestInterface
    {
        return (new ServerRequestFactory())->createServerRequest($method, $path);
    }

    /**
     * Load special fixtures by request
     *
     * @param array<string|int,string> $fixtures
     */
    protected function loadFixtures(array $fixtures): void
    {
        /** @var ContainerInterface $container */
        $container = $this->app()->getContainer();
        $loader = new Loader();
        foreach ($fixtures as $class) { // $name => $class
            /** @var AbstractFixture $fixture */
            $fixture = $container->get($class);
            $loader->addFixture($fixture);
        }

        /** @var EntityManagerInterface $em */
        $em = $container->get(EntityManagerInterface::class);
        $executor = new ORMExecutor($em, new ORMPurger($em));
        $executor->execute($loader->getFixtures());
    }

    protected function app(): App
    {
        if ($this->app === null) {
            $this->app = (require __DIR__ . '/../../config/app.php')($this->container());
        }
        return $this->app;
    }

    private function container(): ContainerInterface
    {
        /** @var ContainerInterface */
        return require __DIR__ . '/../../config/container.php';
    }
}
