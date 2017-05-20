<?php
declare(strict_types=1);

namespace Zelenin\MessageBus\MiddlewareBus;

use SplQueue;
use Zelenin\MessageBus\Context;

final class MiddlewareDispatcher
{
    /**
     * @var SplQueue
     */
    private $stack;

    /**
     * @var Middleware
     */
    private $finalMiddleware;

    /**
     * @var Context
     */
    private $context;

    /**
     * @param SplQueue $stack
     * @param Middleware $finalMiddleware
     */
    public function __construct(SplQueue $stack, Middleware $finalMiddleware)
    {
        $this->stack = $stack;
        $this->finalMiddleware = $finalMiddleware;
        $this->context = new Context();
    }

    /**
     * @inheritdoc
     */
    public function __invoke($message): Context
    {
        if (!$this->stack->valid()) {
            return call_user_func($this->finalMiddleware, $message, $this);
        }

        $middleware = $this->stack->current();
        $this->stack->next();

        $this->context = call_user_func($middleware, $message, $this);
        return $this->context();
    }

    /**
     * @return Context
     */
    public function context(): Context
    {
        return $this->context;
    }

    /**
     * @param Context $context
     *
     * @return MiddlewareDispatcher
     */
    public function withContext(Context $context): self
    {
        $dispatcher = clone $this;
        $dispatcher->context = $context;

        return $dispatcher;
    }
}
