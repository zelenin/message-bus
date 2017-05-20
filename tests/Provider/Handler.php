<?php
declare(strict_types=1);

namespace Zelenin\MessageBus\Test\Provider;

use Zelenin\MessageBus\Context;
use Zelenin\MessageBus\MiddlewareBus\Middleware;

final class Handler implements Middleware\HandlerMiddleware\Handler
{
    /**
     * @inheritdoc
     */
    public function __invoke($message, Context $context): Context
    {
        return $context->withValue('executed', true);
    }
}
