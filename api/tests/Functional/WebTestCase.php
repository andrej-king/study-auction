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
    private ?MailerClient $mailer = null;

    /**
     * Will call it after each test call
     *
     * @return void
     */
    protected function tearDown(): void
    {
        $this->app = null;
        parent::tearDown();
    }

    /**
     * Send request in JSON with params
     *
     * @param string $method
     * @param string $path
     * @param array  $body
     *
     * @return ServerRequestInterface
     * @throws \JsonException
     */
    protected static function json(string $method, string $path, array $body = []): ServerRequestInterface
    {
        $request = self::request($method, $path)
            ->withHeader('Accept', 'application/json')
            ->withHeader('Content-Type', 'application/json');
        $request->getBody()->write(json_encode($body, JSON_THROW_ON_ERROR));
        return $request;
    }

    /**
     * Send request
     *
     * @param string $method
     * @param string $path
     *
     * @return ServerRequestInterface
     */
    protected static function request(string $method, string $path): ServerRequestInterface
    {
        return (new ServerRequestFactory())->createServerRequest($method, $path);
    }

    /**
     * Load special fixtures by request.
     *
     * @param array<string|int,string> $fixtures
     *
     * @return void
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


    /**
     * Create single app object.
     *
     * @return App
     */
    protected function app(): App
    {
        if ($this->app === null) {
            $this->app = (require __DIR__ . '/../../config/app.php')($this->container());
        }
        return $this->app;
    }

    /**
     * Create single MailerClient object.
     *
     * @return MailerClient
     */
    protected function mailer(): MailerClient
    {
        if ($this->mailer === null) {
            $this->mailer = new MailerClient();
        }
        return $this->mailer;
    }

    /**
     * Get container with configs.
     *
     * @return ContainerInterface
     */
    private function container(): ContainerInterface
    {
        /** @var ContainerInterface */
        return require __DIR__ . '/../../config/container.php';
    }
}
