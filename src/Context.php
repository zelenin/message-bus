<?php
declare(strict_types=1);

namespace Zelenin\MessageBus;

final class Context
{
    /**
     * @var array
     */
    private $values;

    public function __construct()
    {
        $this->values = [];
    }

    /**
     * @return array
     */
    public function values(): array
    {
        return $this->values;
    }

    /**
     * @param string $name
     * @param $default
     *
     * @return mixed
     */
    public function value(string $name, $default = null)
    {
        if (!array_key_exists($name, $this->values)) {
            return $default;
        }

        return $this->values[$name];
    }

    /**
     * @param string $name
     * @param $value
     *
     * @return Context
     */
    public function withValue(string $name, $value): self
    {
        $new = clone $this;
        $new->values[$name] = $value;

        return $new;
    }

    /**
     * @param string $name
     *
     * @return Context
     */
    public function withoutValue(string $name): self
    {
        $new = clone $this;
        unset($new->values[$name]);

        return $new;
    }
}
