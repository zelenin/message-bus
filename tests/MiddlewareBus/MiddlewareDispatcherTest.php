<?php
declare(strict_types=1);

namespace Zelenin\MessageBus\Test\MiddlewareBus;

use PHPUnit\Framework\TestCase;
use Zelenin\MessageBus\MiddlewareBus\FinalMiddleware;
use Zelenin\MessageBus\MiddlewareBus\MiddlewareDispatcher;
use Zelenin\MessageBus\MiddlewareBus\MiddlewareStack;
use Zelenin\MessageBus\Test\Provider\MiddlewareA;
use Zelenin\MessageBus\Test\Provider\MiddlewareB;
use Zelenin\MessageBus\Test\Provider\MiddlewareC;
use Zelenin\MessageBus\Test\Provider\MiddlewareD;

final class MiddlewareDispatcherTest extends TestCase
{
    public function testHandle()
    {
        $middlewares = [
            new MiddlewareA(),
            new MiddlewareB(),
            new MiddlewareC(),
            new MiddlewareD(),
        ];

        $dispatcher = new MiddlewareDispatcher(new MiddlewareStack($middlewares), new FinalMiddleware());

        $message = new \stdClass();

        $context = $dispatcher($message);

        $this->assertEquals(count($middlewares), $context->value('i'));
        $this->assertEquals('CDBA', $context->value('order'));
    }

    public function testContext()
    {
        $middlewares = [
            new MiddlewareA(),
            new MiddlewareB(),
            new MiddlewareC(),
            new MiddlewareD(),
        ];

        $dispatcher = new MiddlewareDispatcher(new MiddlewareStack($middlewares), new FinalMiddleware());

        $message = new \stdClass();

        $context = $dispatcher($message);

        $this->assertEquals($context, $dispatcher->context());

        $valueName = 'added';
        $valueValue = 'addedValue';

        $this->assertNull($dispatcher->context()->value($valueName));

        $dispatcher = $dispatcher->withContext($dispatcher->context()->withValue($valueName, $valueValue));
        $this->assertEquals($valueValue, $dispatcher->context()->value($valueName));
    }
}
