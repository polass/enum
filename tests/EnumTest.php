<?php

namespace Polass\Tests;

use PHPUnit\Framework\TestCase;
use Polass\Enum\Enum;
use Polass\Enum\Exceptions\ConstantNotDefinedException;
use Polass\Enum\Exceptions\ConstantNotFoundException;
use Polass\Enum\Exceptions\ConstantWithValueNotFoundException;

class EnumTest extends TestCase
{
    /**
     * `__construct()` のテスト
     *
     */
    public function testConstruct()
    {
        $this->assertNotEmpty(
            $stub = new Stubs\EnumWithDefault('FOO')
        );

        $this->assertNotEmpty(
            $stub = new Stubs\EnumWithDefault('BAR')
        );

        $this->assertNotEmpty(
            $stub = new Stubs\EnumWithoutDefault('FOO')
        );
    }

    /**
     * `key()` のテスト
     *
     */
    public function testKey()
    {
        $this->assertEquals(
            (new Stubs\EnumWithDefault('FOO'))->key(),
            'FOO'
        );
    }

    /**
     * `value()` のテスト
     *
     */
    public function testValue()
    {
        $this->assertEquals(
            (new Stubs\EnumWithDefault('FOO'))->value(),
            Stubs\EnumWithDefault::FOO
        );
    }

    /**
     * `is()` のテスト
     *
     */
    public function testIs()
    {
        $key = 'FOO';
        $instance = new Stubs\EnumWithDefault($key);

        $this->assertTrue(
            $instance->is($key)
        );

        $this->assertTrue(
            $instance->is(new Stubs\EnumWithDefault($key))
        );

        $anotherKey = 'BAR';

        $this->assertFalse(
            $instance->is($anotherKey)
        );

        $this->assertFalse(
            $instance->is(new Stubs\EnumWithDefault($anotherKey))
        );

        $this->assertFalse(
            $instance->is(new Stubs\EnumWithoutDefault($key))
        );

        $this->assertFalse(
            $instance->is(null)
        );
    }

    /**
     * `equals()` のテスト
     *
     */
    public function testEquals()
    {
        $key = 'FOO';
        $value = Stubs\EnumWithDefault::FOO;
        $instance = new Stubs\EnumWithDefault($key);

        $this->assertTrue(
            $instance->equals($value)
        );

        $this->assertTrue(
            $instance->equals(new Stubs\EnumWithDefault($key))
        );

        $anotherKey = 'BAR';
        $anotherValue = Stubs\EnumWithDefault::BAR;

        $this->assertFalse(
            $instance->equals($anotherValue)
        );

        $this->assertFalse(
            $instance->equals(new Stubs\EnumWithDefault($anotherKey))
        );

        $this->assertFalse(
            $instance->equals(new Stubs\EnumWithoutDefault($key))
        );

        $this->assertFalse(
            $instance->equals(null)
        );
    }

    /**
     * `__get()` の正常系のテスト
     *
     */
    public function testGet()
    {
        $this->assertEquals(
            (new Stubs\EnumWithDefault('FOO'))->key,
            'FOO'
        );

        $this->assertEquals(
            (new Stubs\EnumWithDefault('FOO'))->value,
            Stubs\EnumWithDefault::FOO
        );
    }

    /**
     * `__get()` の異常系のテスト
     *
     * @expectedException \BadMethodCallException
     */
    public function testGetFailed()
    {
        $instance = new Stubs\EnumWithDefault('FOO');

        $instance->invalid;
    }

    /**
     * `__toString()` のテスト
     *
     */
    public function testToString()
    {
        $instance = new Stubs\EnumWithDefault('FOO');

        $this->assertTrue(
            is_string($instance->__toString())
        );

        $this->assertTrue(
            is_string((string)$instance)
        );
    }

    /**
     * `make()` の正常系のテスト
     *
     */
    public function testMake()
    {
        $this->assertNull(
            Stubs\EnumWithoutDefault::make(null, true)
        );

        $this->assertNotEmpty(
            Stubs\EnumWithDefault::make('FOO', true)
        );

        $this->assertNotEmpty(
            Stubs\EnumWithDefault::make('FOO', false)
        );
    }

    /**
     * `make()` の異常系のテスト (ConstantNotFoundException)
     *
     * @dataProvider provideInvalidMakeParameters
     */
    public function testMakeFailedWithConstantNotFoundException($key, $nullable)
    {
        $this->expectException(
            ConstantNotFoundException::class
        );

        Stubs\EnumWithoutDefault::make($key, $nullable);
    }

    /**
     * `make()` の異常系のテストのためのデータプロバイダ
     *
     */
    public function provideInvalidMakeParameters()
    {
        return [
            [ 'BAZ', true ],
            [ 'BAZ', false ],
            [ '', true ],
            [ '', false ],
        ];
    }

    /**
     * `make()` の異常系のテスト (ConstantNotDefinedException)
     *
     */
    public function testMakeFailedWithConstantNotDefinedException()
    {
        $this->expectException(
            ConstantNotDefinedException::class
        );

        Stubs\EnumWithoutDefault::make(null, false);
    }

    /**
     * `from()` の正常系のテスト
     *
     */
    public function testFrom()
    {
        $this->assertEquals(
            Stubs\EnumWithoutDefault::from(Stubs\EnumWithoutDefault::FOO, true)->value,
            Stubs\EnumWithoutDefault::FOO
        );

        $this->assertEquals(
            Stubs\EnumWithoutDefault::from(Stubs\EnumWithoutDefault::FOO, false)->value,
            Stubs\EnumWithoutDefault::FOO
        );

        $this->assertNull(
            Stubs\EnumWithoutDefault::from('baz', true)
        );

        $this->assertNull(
            Stubs\EnumWithoutDefault::from(null, true)
        );
    }

    /**
     * `from()` の異常系のテスト
     *
     * @dataProvider provideInvalidFromParameters
     */
    public function testFromFailed($value, $nullable)
    {
        $this->expectException(
            ConstantWithValueNotFoundException::class
        );

        Stubs\EnumWithoutDefault::from($value, $nullable);
    }

    /**
     * `from()` の異常系のテストのためのデータプロバイダ
     *
     */
    public function provideInvalidFromParameters()
    {
        return [
            [ 'baz', false ],
            [ null, false ],
        ];
    }

    /**
     * `constants()` のテスト
     *
     */
    public function testConstants()
    {
        $this->assertArrayHasKey('FOO', Stubs\EnumWithDefault::constants());
        $this->assertArrayHasKey('BAR', Stubs\EnumWithDefault::constants());
        $this->assertArrayHasKey('__default', Stubs\EnumWithDefault::constants());
    }

    /**
     * `constantsWithoutDefault()` のテスト
     *
     */
    public function testConstantsWithoutDefault()
    {
        $this->assertArrayHasKey('FOO', Stubs\EnumWithoutDefault::constantsWithoutDefault());
        $this->assertArrayHasKey('BAR', Stubs\EnumWithoutDefault::constantsWithoutDefault());
        $this->assertArrayNotHasKey('__default', Stubs\EnumWithoutDefault::constantsWithoutDefault());
    }

    /**
     * `keys()` のテスト
     *
     */
    public function testKeys()
    {
        $this->assertContains('FOO', Stubs\EnumWithDefault::keys());
        $this->assertContains('BAR', Stubs\EnumWithDefault::keys());
        $this->assertNotContains('__default', Stubs\EnumWithDefault::keys());
    }

    /**
     * `values()` のテスト
     *
     */
    public function testValues()
    {
        $this->assertContains('foo', Stubs\EnumWithDefault::values());
        $this->assertContains('bar', Stubs\EnumWithDefault::values());
        $this->assertNotContains('FOO', Stubs\EnumWithDefault::values());
    }

    /**
     * `implodedKeys()` のテスト
     *
     */
    public function testImplodedKeys()
    {
        $this->assertEquals('FOO,BAR', Stubs\EnumWithDefault::implodedKeys());
        $this->assertEquals('FOO|BAR', Stubs\EnumWithDefault::implodedKeys('|'));
    }

    /**
     * `implodedValues()` のテスト
     *
     */
    public function testImplodedValues()
    {
        $this->assertEquals('foo,bar', Stubs\EnumWithDefault::implodedValues());
        $this->assertEquals('foo|bar', Stubs\EnumWithDefault::implodedValues('|'));
    }

    /**
     * `has()` のテスト
     *
     */
    public function testHas()
    {
        $this->assertTrue(Stubs\EnumWithDefault::has('FOO'));
        $this->assertTrue(Stubs\EnumWithDefault::has('BAR'));
        $this->assertFalse(Stubs\EnumWithDefault::has('__default'));
    }

    /**
     * `hasDefault()` のテスト
     *
     */
    public function testHasDefault()
    {
        $this->assertTrue(Stubs\EnumWithDefault::hasDefault());
        $this->assertFalse(Stubs\EnumWithoutDefault::hasDefault());
    }

    /**
     * `getDefaultValue()` の正常系のテスト
     *
     */
    public function testGetDefaultValue()
    {
        $this->assertEquals(
            Stubs\EnumWithDefault::getDefaultValue(),
            Stubs\EnumWithDefault::__default
        );
    }

    /**
     * `getDefaultValue()` の異常系のテスト
     *
     */
    public function testGetDefaultValueFailed()
    {
        $this->expectException(
            ConstantNotDefinedException::class
        );

        Stubs\EnumWithoutDefault::getDefaultValue();
    }

    /**
     * `default()` の正常系のテスト
     *
     */
    public function testDefault()
    {
        $this->assertEquals(
            Stubs\EnumWithDefault::default()->key,
            Stubs\EnumWithDefault::__default
        );
    }

    /**
     * `default()` の異常系のテスト
     *
     */
    public function testDefaultFailed()
    {
        $this->expectException(
            ConstantNotDefinedException::class
        );

        Stubs\EnumWithoutDefault::default();
    }

    /**
     * `valueOf()` の正常系のテスト
     *
     */
    public function testValueOf()
    {
        $this->assertEquals(
            Stubs\EnumWithDefault::valueOf('FOO', true),
            Stubs\EnumWithDefault::FOO
        );

        $this->assertEquals(
            Stubs\EnumWithDefault::valueOf('FOO', false),
            Stubs\EnumWithDefault::FOO
        );

        $this->assertNull(
            Stubs\EnumWithDefault::valueOf('BAZ', false)
        );

        $this->assertNull(
            Stubs\EnumWithoutDefault::valueOf('BAZ', false)
        );
    }

    /**
     * `valueOf()` の異常系のテスト
     *
     * @dataProvider provideInvalidValueOfParameters
     */
    public function testValueOfFailed($key, $throws)
    {
        $this->expectException(
            ConstantNotFoundException::class
        );

        Stubs\EnumWithDefault::valueOf($key, $throws);
    }

    /**
     * `valueOf()` の異常系のテストのデータプロバイダ
     *
     */
    public function provideInvalidValueOfParameters()
    {
        return [
            [ 'BAZ', true ],
            [ '', true ],
            [ null, true ],
        ];
    }

    /**
     * `keyOf()` の正常系のテスト
     *
     */
    public function testKeyOf()
    {
        $this->assertEquals(
            Stubs\EnumWithDefault::keyOf('foo', true),
            'FOO'
        );

        $this->assertEquals(
            Stubs\EnumWithDefault::keyOf('foo', false),
            'FOO'
        );

        $this->assertNull(
            Stubs\EnumWithDefault::keyOf(null, false)
        );

        $this->assertNull(
            Stubs\EnumWithoutDefault::keyOf(null, false)
        );

        $this->assertFalse(
            is_array(
                Stubs\EnumWithDuplicateValues::keyOf('single', true)
            )
        );

        $this->assertTrue(
            is_array(
                Stubs\EnumWithDuplicateValues::keyOf('duplicated', true)
            )
        );

        $this->assertContains(
            'FOO',
            Stubs\EnumWithDuplicateValues::keyOf('duplicated', true)
        );

        $this->assertContains(
            'BAR',
            Stubs\EnumWithDuplicateValues::keyOf('duplicated', true)
        );
    }

    /**
     * `keyOf()` の異常系のテスト
     *
     * @dataProvider provideInvalidKeyOfParameters
     */
    public function testKeyOfFailed($value, $throws)
    {
        $this->expectException(
            ConstantWithValueNotFoundException::class
        );

        Stubs\EnumWithDefault::keyOf($value, $throws);
    }

    /**
     * `keyOf()` の異常系のテストのデータプロバイダ
     *
     */
    public function provideInvalidKeyOfParameters()
    {
        return [
            [ 'baz', true ],
            [ '', true ],
            [ null, true ],
        ];
    }

    /**
     * `__callStatic()` の正常系のテスト
     *
     */
    public function testCallStatic()
    {
        $this->assertEquals(
            Stubs\EnumWithDefault::FOO()->key,
            'FOO'
        );
    }

    /**
     * `__callStatic()` の異常系のテスト
     *
     */
    public function testCallStaticFailed()
    {
        $this->expectException(
            ConstantNotFoundException::class
        );

        Stubs\EnumWithDefault::BAZ();
    }
}
