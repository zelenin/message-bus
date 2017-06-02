<?php
declare(strict_types=1);

namespace Zelenin\MessageBus\Locator;

use RuntimeException;
use Zelenin\MessageBus\Handler;
use Zelenin\MessageBus\Locator\HandlerResolver\HandlerResolver;
use Zelenin\MessageBus\Locator\Provider\Provider;

final class ProviderLocator implements Locator
{
    /**
     * @var Provider
     */
    private $provider;

    /**
     * @var HandlerResolver
     */
    private $resolver;

    /**
     * @param Provider $provider
     * @param HandlerResolver $resolver
     */
    public function __construct(Provider $provider, HandlerResolver $resolver)
    {
        $this->provider = $provider;
        $this->resolver = $resolver;
    }

    /**
     * @inheritdoc
     */
    public function getHandler($message): Handler
    {
        $messageName = get_class($message);

        if (!isset($this->provider->getHandlers()[$messageName])) {
            throw new RuntimeException(sprintf('No handler for message "%s"', $messageName));
        }

        return $this->resolver->getHandler($this->provider->getHandlers()[$messageName]);
    }
}
