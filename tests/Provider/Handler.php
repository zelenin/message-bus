<?php
declare(strict_types=1);

namespace Zelenin\MessageBus\Test\Provider;

use Zelenin\MessageBus\Context;
use Zelenin\MessageBus\Locator\Annotation\HandlerAnnotation;

/**
 * @HandlerAnnotation(message=Message::class)
 */
final class Handler implements \Zelenin\MessageBus\Handler
{
    /**
     * @inheritdoc
     */
    public function __invoke($message, Context $context): Context
    {
        return $context->withValue('executed', true);
    }
}
