<?php
declare(strict_types=1);

namespace Zelenin\MessageBus\Locator\Annotation;

/**
 * @Annotation
 * @Target({"CLASS"})
 */
final class HandlerAnnotation
{
    /**
     * @Required
     *
     * @var string
     */
    public $message;
}
