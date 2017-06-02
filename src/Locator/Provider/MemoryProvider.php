<?php
declare(strict_types=1);

namespace Zelenin\MessageBus\Locator\Provider;

final class MemoryProvider implements Provider
{
    /**
     * @var array
     */
    private $handlers;

    /**
     * @param array $handlers
     */
    public function __construct(array $handlers)
    {
        $this->handlers = [];
        array_walk($handlers, function (string $handlerName, string $messageName) {
            $this->handlers[$messageName] = $handlerName;
        });
    }

    /**
     * @return array
     */
    public function getHandlers(): array
    {
        return $this->handlers;
    }
}
