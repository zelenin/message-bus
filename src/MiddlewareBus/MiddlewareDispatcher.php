<?php
declare(strict_types=1);

namespace Zelenin\MessageBus\MiddlewareBus;

use SplQueue;
use Zelenin\MessageBus\Context;

final class MiddlewareDispatcher
{
    /**
     * @var MiddlewareStack
     */
    private $middlewareStack;

    /**
     * @var Middleware
     */
    private $finalMiddleware;

    /**
     * @var Context
     */
    private $context;

    /**
     * @param MiddlewareStack $middlewareStack
     * @param Middleware $finalMiddleware
     */
    public function __construct(MiddlewareStack $middlewareStack, Middleware $finalMiddleware)
    {
        $this->middlewareStack = $middlewareStack;
        $this->finalMiddleware = $finalMiddleware;
        $this->context = new Context();

        $middlewareStack->reset();
    }

    /**
     * @inheritdoc
     */
    public function __invoke($message): Context
    {
        if (!$this->middlewareStack->isValid()) {
            return call_user_func($this->finalMiddleware, $message, $this);
        }

        $nextMiddleware = $this->middlewareStack->next();

        $this->context = $nextMiddleware($message, $this);

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
