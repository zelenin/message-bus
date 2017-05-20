<?php
declare(strict_types=1);

namespace Zelenin\MessageBus\Test\Provider;

use Zelenin\MessageBus\Context;
use Zelenin\MessageBus\MiddlewareBus\Middleware;
use Zelenin\MessageBus\MiddlewareBus\MiddlewareDispatcher;

final class MiddlewareC implements Middleware
{
    /**
     * @inheritdoc
     */
    public function __invoke($message, MiddlewareDispatcher $dispatcher): Context
    {
        $context = $dispatcher->context();

        $i = $context->value('i', 0);
        $order = $context->value('order', '');

        $context = $context
            ->withValue('i', ++$i)
            ->withValue('order', $order . 'C');

        $dispatcher = $dispatcher->withContext($context);

        return $dispatcher($message);
    }
}
