<?php
declare(strict_types=1);

namespace Zelenin\MessageBus;

final class NullHandler implements Handler
{
    /**
     * @inheritdoc
     */
    public function __invoke($message, Context $context): Context
    {
        return $context;
    }
}
