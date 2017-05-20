<?php
declare(strict_types=1);

namespace Zelenin\MessageBus\MiddlewareBus;

use Zelenin\MessageBus\Context;

interface Middleware
{
    /**
     * @param object $message
     * @param MiddlewareDispatcher $dispatcher
     *
     * @return Context
     */
    public function __invoke($message, MiddlewareDispatcher $dispatcher): Context;
}
