<?php
declare(strict_types=1);

namespace Zelenin\MessageBus\Test\Locator;

use PHPUnit\Framework\TestCase;
use Zelenin\MessageBus\Locator\MemoryLocator;
use Zelenin\MessageBus\Test\Provider\Handler;
use Zelenin\MessageBus\Test\Provider\Message;

final class MemoryLocatorTest extends TestCase
{
    public function testLocator()
    {
        $handler = new Handler();
        $handlers = [
            Message::class => $handler,
        ];

        $locator = new MemoryLocator($handlers);

        $this->assertEquals($handler, $locator->getHandler(new Message()));
    }

    public function testNotFoundHandler()
    {
        $this->expectException(\RuntimeException::class);

        $handler = new Handler();
        $handlers = [
            'message' => $handler,
        ];

        $locator = new MemoryLocator($handlers);
        $locator->getHandler(new Message());
    }
}
