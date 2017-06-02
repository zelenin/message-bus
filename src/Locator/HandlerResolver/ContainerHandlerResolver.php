<?php
declare(strict_types=1);

namespace Zelenin\MessageBus\Locator\HandlerResolver;

use Psr\Container\ContainerInterface;
use Zelenin\MessageBus\Handler;

final class ContainerHandlerResolver implements HandlerResolver
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param string $handlerName
     *
     * @return Handler
     */
    public function getHandler(string $handlerName): Handler
    {
        return $this->container->get($handlerName);
    }
}
