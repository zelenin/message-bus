<?php
declare(strict_types=1);

namespace Zelenin\MessageBus\MiddlewareBus;

use Zelenin\MessageBus\Context;
use Zelenin\MessageBus\MessageBus;

final class MiddlewareBus implements MessageBus
{
    /**
     * @var MiddlewareDispatcher
     */
    private $dispatcher;

    /**
     * @param MiddlewareStack $middlewareStack
     */
    public function __construct(MiddlewareStack $middlewareStack)
    {
        $this->dispatcher = new MiddlewareDispatcher($middlewareStack, new FinalMiddleware());
    }

    /**
     * @inheritdoc
     */
    public function handle($message): Context
    {
        return call_user_func($this->dispatcher, $message);
    }
}
