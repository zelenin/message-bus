<?php
declare(strict_types=1);

namespace Zelenin\MessageBus\MiddlewareBus;

use Zelenin\MessageBus\Context;
use Zelenin\MessageBus\MessageBus;

final class MiddlewareBus implements MessageBus
{
    /**
     * @var MiddlewareStack
     */
    private $middlewareStack;

    /**
     * @param MiddlewareStack $middlewareStack
     */
    public function __construct(MiddlewareStack $middlewareStack)
    {
        $this->middlewareStack = $middlewareStack;
    }

    /**
     * @inheritdoc
     */
    public function handle($message): Context
    {
        return call_user_func($this->middlewareStack, $message);
    }
}
