<?php
declare(strict_types=1);

namespace Zelenin\MessageBus\Test\MiddlewareBus\Middleware;

use PHPUnit\Framework\TestCase;
use Zelenin\MessageBus\Context;
use Zelenin\MessageBus\MiddlewareBus\FinalMiddleware;
use Zelenin\MessageBus\MiddlewareBus\MiddlewareDispatcher;
use Zelenin\MessageBus\MiddlewareBus\MiddlewareStack;

final class FinalMiddlewareTest extends TestCase
{
    public function testFinalMiddleware()
    {
        $middleware = new FinalMiddleware();

        $message = new \stdClass();
        $dispatcher = new MiddlewareDispatcher(new MiddlewareStack([]), $middleware);

        $this->assertInstanceOf(Context::class, $middleware($message, $dispatcher));
        $this->assertEquals((new Context())->values(), $middleware($message, $dispatcher)->values());
    }
}
