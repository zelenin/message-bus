<?php
declare(strict_types=1);

namespace Zelenin\MessageBus;

interface Handler
{
    /**
     * @param object $message
     *
     * @return Context
     */
    public function __invoke($message, Context $context): Context;
}
