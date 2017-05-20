<?php
declare(strict_types=1);

namespace Zelenin\MessageBus\MiddlewareBus\Middleware;

use Zelenin\MessageBus\Context;
use Zelenin\MessageBus\MiddlewareBus\Middleware;
use Zelenin\MessageBus\MiddlewareBus\Middleware\HandlerMiddleware\Locator\Locator;
use Zelenin\MessageBus\MiddlewareBus\MiddlewareDispatcher;

final class HandlerMiddleware implements Middleware
{
    /**
     * @var Locator
     */
    private $locator;

    /**
     * @param Locator $locator
     */
    public function __construct(Locator $locator)
    {
        $this->locator = $locator;
    }

    /**
     * @inheritdoc
     */
    public function __invoke($message, MiddlewareDispatcher $dispatcher): Context
    {
        $context = call_user_func($this->locator->getHandler($message), $message, $dispatcher->context());

        $dispatcher = $dispatcher->withContext($context);

        return $dispatcher($message);
    }
}
