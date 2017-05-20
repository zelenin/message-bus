<?php
declare(strict_types=1);

namespace Zelenin\MessageBus\Test;

use PHPUnit\Framework\TestCase;
use Zelenin\MessageBus\Context;

final class ContextTest extends TestCase
{
    /**
     * @dataProvider contextProvider
     */
    public function testValues(Context $context, array $values)
    {
        $this->assertEquals($context->values(), $values);
    }

    /**
     * @dataProvider contextProvider
     */
    public function testValue(Context $context, array $values)
    {
        foreach ($values as $name => $value) {
            $this->assertEquals($context->value($name), $value);
        }

        /** for skip risky */
        $this->assertTrue(true);
    }

    /**
     * @dataProvider contextProvider
     */
    public function testDefaultValue(Context $context, array $values)
    {
        $defaultValue = 5;
        $this->assertEquals($context->value(uniqid('', true), $defaultValue), $defaultValue);
    }

    /**
     * @dataProvider contextProvider
     */
    public function testWithValue(Context $context, array $values)
    {
        $name = 'addedName';
        $value = 'addedValue';

        $this->assertNull($context->value($name));

        $context = $context->withValue($name, $value);

        $this->assertEquals($context->value($name), $value);
    }

    /**
     * @dataProvider contextProvider
     */
    public function testWithoutValue(Context $context, array $values)
    {
        foreach ($values as $name => $value) {
            $this->assertEquals($context->value($name), $value);

            $context = $context->withoutValue($name);

            $this->assertNull($context->value($name));
        }

        /** for skip risky */
        $this->assertTrue(true);
    }

    /**
     * @return array
     */
    public function contextProvider(): array
    {
        $emptyContext = new Context();

        $values = [
            'string' => 'value',
            'integer' => 5,
        ];

        $contextWithValues = new Context();

        foreach ($values as $name => $value) {
            $contextWithValues = $contextWithValues->withValue($name, $value);
        }

        return [
            [$emptyContext, []],
            [$contextWithValues, $values],
        ];
    }
}
