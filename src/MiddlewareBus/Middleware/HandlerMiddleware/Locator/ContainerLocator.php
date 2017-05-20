<?php
declare(strict_types=1);

namespace Zelenin\MessageBus\MiddlewareBus\Middleware\HandlerMiddleware\Locator;

use InvalidArgumentException;
use Psr\Container\ContainerInterface;
use RuntimeException;
use Zelenin\MessageBus\MiddlewareBus\Middleware\HandlerMiddleware\Handler;


final class ContainerLocator implements Locator
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var array
     */
    private $handlers;

    /**
     * @param ContainerInterface $container
     * @param array $handlers
     */
    public function __construct(ContainerInterface $container, array $handlers)
    {
        $this->container = $container;
        array_walk($handlers, function (string $handlerName, string $messageName) {
            if (isset($this->handlers[$messageName])) {
                throw new InvalidArgumentException(sprintf('Handler for "%s" already exists.', $messageName));
            }
            $this->handlers[$messageName] = $handlerName;
        });
    }

    /**
     * @inheritdoc
     */
    public function getHandler($message): Handler
    {
        $messageName = get_class($message);

        if (!isset($this->handlers[$messageName])) {
            throw new RuntimeException(sprintf('No handler for message "%s"', $messageName));
        }

        return $this->container->get($this->handlers[$messageName]);
    }
}
