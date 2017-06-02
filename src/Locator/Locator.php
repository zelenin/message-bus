<?php
declare(strict_types=1);

namespace Zelenin\MessageBus\Locator;

use Zelenin\MessageBus\Handler;

interface Locator
{
    /**
     * @param object $message
     *
     * @return Handler
     */
    public function getHandler($message): Handler;
}
