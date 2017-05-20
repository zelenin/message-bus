<?php
declare(strict_types=1);

namespace Zelenin\MessageBus\MiddlewareBus;

use SplQueue;
use Zelenin\MessageBus\Context;

final class MiddlewareStack
{
    /**
     * @var SplQueue
     */
    private $stack;

    /**
     * @var Middleware
     */
    private $dispatcher;

    /**
     * @param Middleware[] $middlewares
     */
    public function __construct(array $middlewares)
    {
        $this->stack = new SplQueue();

        array_walk($middlewares, function (Middleware $middleware) {
            $this->stack->push($middleware);
        });

        $this->dispatcher = new MiddlewareDispatcher($this->stack, new FinalMiddleware());
    }

    /**
     * @param object $message
     *
     * @return Context
     */
    public function __invoke($message): Context
    {
        $this->stack->rewind();

        return call_user_func($this->dispatcher, $message);
    }
}
