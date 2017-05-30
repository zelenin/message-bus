<?php
declare(strict_types=1);

namespace Zelenin\MessageBus\Test\MiddlewareBus\Middleware;

use PHPUnit\Framework\TestCase;
use Zelenin\MessageBus\MiddlewareBus\MiddlewareStack;
use Zelenin\MessageBus\Test\Provider\MiddlewareA;
use Zelenin\MessageBus\Test\Provider\MiddlewareB;
use Zelenin\MessageBus\Test\Provider\MiddlewareC;
use Zelenin\MessageBus\Test\Provider\MiddlewareD;

final class MiddlewareStackTest extends TestCase
{
    public function testMiddlewareStack()
    {
        $middlewares = [
            new MiddlewareA(),
            new MiddlewareB(),
            new MiddlewareC(),
            new MiddlewareD(),
        ];

        $middlewareStack = new MiddlewareStack($middlewares);

        $middlewareStack->reset();

        $count = 0;
        while ($middlewareStack->isValid()) {
            $middlewareStack->next();
            $count++;
        }

        $this->assertEquals(count($middlewares), $count);
    }
}
