<?php
declare(strict_types=1);

namespace Zelenin\MessageBus;

interface MessageBus
{
    /**
     * @param object $message
     *
     * @return Context
     */
    public function handle($message): Context;
}
