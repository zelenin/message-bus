<?php
declare(strict_types=1);

namespace Zelenin\MessageBus\Test\Locator;

use PHPUnit\Framework\TestCase;
use Zelenin\MessageBus\Locator\Provider\MemoryProvider;
use Zelenin\MessageBus\Test\Provider\Handler;
use Zelenin\MessageBus\Test\Provider\Message;

final class MemoryProviderTest extends TestCase
{
    public function testProvider()
    {
        $handler = new Handler();
        $handlers = [
            Message::class => get_class($handler),
        ];

        $provider = new MemoryProvider($handlers);

        $this->assertEquals($handlers, $provider->getHandlers());
    }
}
