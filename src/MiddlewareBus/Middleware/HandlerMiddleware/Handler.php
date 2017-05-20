<?php
declare(strict_types=1);

namespace Zelenin\MessageBus\MiddlewareBus\Middleware\HandlerMiddleware;

use Zelenin\MessageBus\Context;

interface Handler
{
    /**
     * @param object $message
     *
     * @return Context
     */
    public function __invoke($message, Context $context): Context;
}
