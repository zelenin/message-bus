<?php
declare(strict_types=1);

namespace Zelenin\MessageBus\Test\Provider;

use Zelenin\MessageBus\Context;
use Zelenin\MessageBus\MiddlewareBus\Middleware;
use Zelenin\MessageBus\MiddlewareBus\MiddlewareDispatcher;

final class MiddlewareB implements Middleware
{
    /**
     * @inheritdoc
     */
    public function __invoke($message, MiddlewareDispatcher $dispatcher): Context
    {
        $context = $dispatcher($message);

        $i = $context->value('i', 0);
        $order = $context->value('order', '');

        return $context
            ->withValue('i', ++$i)
            ->withValue('order', $order . 'B');
    }
}
