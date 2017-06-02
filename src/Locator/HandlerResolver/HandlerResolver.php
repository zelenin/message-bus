<?php
declare(strict_types=1);

namespace Zelenin\MessageBus\Locator\HandlerResolver;

use Zelenin\MessageBus\Handler;

interface HandlerResolver
{
    /**
     * @param string $handlerName
     *
     * @return Handler
     */
    public function getHandler(string $handlerName): Handler;
}
