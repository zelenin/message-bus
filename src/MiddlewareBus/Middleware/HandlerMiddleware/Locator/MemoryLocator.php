<?php
declare(strict_types=1);

namespace Zelenin\MessageBus\MiddlewareBus\Middleware\HandlerMiddleware\Locator;

use InvalidArgumentException;
use RuntimeException;
use Zelenin\MessageBus\MiddlewareBus\Middleware\HandlerMiddleware\Handler;

final class MemoryLocator implements Locator
{
    /**
     * @var Handler[]
     */
    private $handlers;

    /**
     * @param Handler[] $handlers
     */
    public function __construct(array $handlers = [])
    {
        $this->handlers = [];
        array_walk($handlers, function (Handler $handler, string $messageName) {
            if (isset($this->handlers[$messageName])) {
                throw new InvalidArgumentException(sprintf('Handler for "%s" already exists.', $messageName));
            }
            $this->handlers[$messageName] = $handler;
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

        return $this->handlers[$messageName];
    }
}
