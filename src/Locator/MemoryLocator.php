<?php
declare(strict_types=1);

namespace Zelenin\MessageBus\Locator;

use InvalidArgumentException;
use Zelenin\MessageBus\Handler;
use Zelenin\MessageBus\NullHandler;

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
            return new NullHandler();
        }

        return $this->handlers[$messageName];
    }
}
