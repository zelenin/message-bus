<?php
declare(strict_types=1);

namespace Zelenin\MessageBus\Locator\Provider;

interface Provider
{
    /**
     * @return array
     */
    public function getHandlers(): array;
}
