<?php
declare(strict_types=1);

namespace Zelenin\MessageBus\Test\Locator;

use PHPUnit\Framework\TestCase;
use Zelenin\MessageBus\Locator\Provider\AnnotationProvider;
use Zelenin\MessageBus\Test\Provider\Handler;
use Zelenin\MessageBus\Test\Provider\Message;

final class AnnotationProviderTest extends TestCase
{
    public function testProvider()
    {
        $handler = new Handler();
        $handlers = [
            Message::class => get_class($handler),
        ];

        $provider = new AnnotationProvider(__DIR__ . '/../../Provider');

        $this->assertEquals($handlers, $provider->getHandlers());
    }
}
