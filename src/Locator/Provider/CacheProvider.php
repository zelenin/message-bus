<?php
declare(strict_types=1);

namespace Zelenin\MessageBus\Locator\Provider;

final class CacheProvider implements Provider
{
    /**
     * @var string
     */
    private $path;

    /**
     * @var Provider
     */
    private $provider;

    /**
     * @param string $path
     * @param Provider $provider
     */
    public function __construct(string $path, Provider $provider)
    {
        $this->path = $path;
        $this->provider = $provider;
    }


    /**
     * @return array
     */
    public function getHandlers(): array
    {
        if (!$this->isExist()) {
            file_put_contents($this->path, '<?php return ' . var_export($this->provider->getHandlers(), true) . ';' . "\n");
        }

        return require $this->path;
    }

    /**
     * @return bool
     */
    private function isExist(): bool
    {
        return is_file($this->path);
    }
}
