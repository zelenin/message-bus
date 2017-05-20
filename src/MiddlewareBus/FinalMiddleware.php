<?php
declare(strict_types=1);

namespace Zelenin\MessageBus\MiddlewareBus;

use Zelenin\MessageBus\Context;

final class FinalMiddleware implements Middleware
{
    /**
     * @inheritdoc
     */
    public function __invoke($message, MiddlewareDispatcher $dispatcher): Context
    {
        return $dispatcher->context();
    }
}
