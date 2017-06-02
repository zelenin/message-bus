<?php
declare(strict_types=1);

namespace Zelenin\MessageBus\Test\MiddlewareBus;

use PHPUnit\Framework\TestCase;
use Zelenin\MessageBus\Locator\MemoryLocator;
use Zelenin\MessageBus\MiddlewareBus\Middleware\HandlerMiddleware;
use Zelenin\MessageBus\MiddlewareBus\MiddlewareBus;
use Zelenin\MessageBus\MiddlewareBus\MiddlewareStack;
use Zelenin\MessageBus\Test\Provider\Handler;
use Zelenin\MessageBus\Test\Provider\MiddlewareA;
use Zelenin\MessageBus\Test\Provider\MiddlewareB;
use Zelenin\MessageBus\Test\Provider\MiddlewareC;
use Zelenin\MessageBus\Test\Provider\MiddlewareD;

final class MiddlewareBusTest extends TestCase
{
    public function testMiddlewareBus()
    {
        $middlewares = [
            new MiddlewareA(),
            new MiddlewareB(),
            new MiddlewareC(),
            new MiddlewareD(),
        ];

        $bus = new MiddlewareBus(new MiddlewareStack($middlewares));

        $message = new \stdClass();

        $context = $bus->handle($message);

        $this->assertEquals(count($middlewares), $context->value('i'));
        $this->assertEquals('CDBA', $context->value('order'));
    }

    public function testHandlers()
    {
        $handlers = [
            \stdClass::class => new Handler(),
        ];

        $middlewares = [
            new HandlerMiddleware(new MemoryLocator($handlers)),
        ];

        $bus = new MiddlewareBus(new MiddlewareStack($middlewares));

        $message = new \stdClass();

        $context = $bus->handle($message);

        $this->assertTrue($context->value('executed'));
    }
}
