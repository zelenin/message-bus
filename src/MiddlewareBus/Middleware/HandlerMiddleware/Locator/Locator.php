<?php
declare(strict_types=1);

namespace Zelenin\MessageBus\MiddlewareBus\Middleware\HandlerMiddleware\Locator;

use Zelenin\MessageBus\MiddlewareBus\Middleware\HandlerMiddleware\Handler;

interface Locator
{
    /**
     * @param object $message
     *
     * @return Handler
     */
    public function getHandler($message): Handler;
}
