<?php

namespace Polass\Enum;

use InvalidArgumentException;
use BadMethodCallException;
use ReflectionClass;
use Polass\Enum\Exceptions\ConstantNotDefinedException;
use Polass\Enum\Exceptions\ConstantNotFoundException;
use Polass\Enum\Exceptions\ConstantWithValueNotFoundException;

abstract class Enum
{
    /**
     * 定数のキャッシュ
     *
     * @var array
     */
    private static $_constants = [];

    /**
     * デフォルト値を表す定数の名前
     *
     * @var string
     */
    protected static $__default = '__default';

    /**
     * インスタンスが持つ定数の名前
     *
     * @var string
     */
    protected $key;

    /**
     * インスタンスが持つ値
     *
     * @var mixed
     */
    protected $value;

    /**
     * コンストラクタ
     *
     * @param string $key
     * @return void
     *
     * @throws \Polass\Enum\Exceptions\ConstantNotFoundException
     */
    public function __construct($key)
    {
        $this->key = $key;
        $this->value = static::valueOf($key);
    }

    /**
     * 定数名を取得
     *
     * @return string
     */
    public function key()
    {
        return $this->key;
    }

    /**
     * 値を取得
     *
     * @return mixed
     */
    public function value()
    {
        return $this->value;
    }

    /**
     * 定数名を比較
     *
     * @param mixed $key
     * @return bool
     */
    public function is($key)
    {
        if ($key instanceOf static)
        {
            return $this->key === $key->key;
        }

        return $this->key === $key;
    }

    /**
     * 値を比較
     *
     * @param mixed $value
     * @return bool
     */
    public function equals($value)
    {
        if ($value instanceOf static)
        {
            return $this->value === $value->value;
        }

        return $this->value === $value;
    }

    /**
     * プロパティアクセス
     *
     * @param string $method
     * @return mixed
     *
     * @throws BadMethodCallException
     */
    public function __get($method)
    {
        if (! in_array($method, [ 'key', 'value' ])) {
            throw new BadMethodCallException(sprintf('Method `%s` does not exists on [%s].', $method, static::class));
        }

        return $this->$method();
    }

    /**
     * 文字列として取得する際は定数値を文字列化して返す
     *
     * @return string
     */
    public function __toString()
    {
        return (string)$this->value;
    }

    /**
     * 定数名からインスタンスを作成
     *
     * @param string $key
     * @param bool $nullable
     * @return $this
     *
     * @throws \Polass\Enum\Exceptions\ConstantNotFoundException
     */
    public static function make($key = null, $nullable = false)
    {
        if ($key === null) {
            if (! static::hasDefault() && $nullable) {
                return null;
            }

            $key = static::getDefaultValue();
        }

        return new static($key);
    }

    /**
     * 定数値からインスタンスを作成
     *
     * @param mixed $value
     * @param bool $nullable
     * @return $this
     *
     * @throws \Polass\Enum\Exceptions\ConstantNotFoundException
     */
    public static function from($value, $nullable = false)
    {
        return static::make(static::keyOf($value, ! $nullable), $nullable);
    }

    /**
     * 継承した Enum クラスが持つ定数の値を定数名をキーとして配列で取得
     *
     * @return array
     */
    public static function constants()
    {
        $class = get_called_class();

        if (! array_key_exists($class, self::$_constants))
        {
            self::$_constants[$class] = (new ReflectionClass($class))->getConstants();
        }

        return self::$_constants[$class];
    }

    /**
     * 継承した Enum クラスが持つ __default 以外の定数の値を定数名をキーとして配列で取得
     *
     * @return array
     */
    public static function constantsWithoutDefault()
    {
        $constants = static::constants();

        if (static::hasDefault()) {
            unset($constants[static::$__default]);
        }

        return $constants;
    }

    /**
     * Enum が取り得るキーを配列で取得
     *
     * @return array
     */
    public static function keys()
    {
        return array_keys(static::constantsWithoutDefault());
    }

    /**
     * Enum が取り得る値を配列で取得
     *
     * @return array
     */
    public static function values()
    {
        return array_values(static::constantsWithoutDefault());
    }

    /**
     * その名前の定数を持っているか
     *
     * @param string $key
     * @return bool
     */
    public static function has($key)
    {
        return array_key_exists($key, static::constantsWithoutDefault());
    }

    /**
     * デフォルト値を持っているか
     *
     * @return bool
     */
    public static function hasDefault()
    {
        return array_key_exists(static::$__default, static::constants());
    }

    /**
     * デフォルト値を取得
     *
     * @return string
     *
     * @throws \Polass\Enum\Exceptions\ConstantNotDefinedException
     */
    public static function getDefaultValue($nullable = false)
    {
        if (static::hasDefault()) {
            return static::constants()[static::$__default];
        }

        if (! $nullable) {
            throw (new ConstantNotDefinedException)->setConstant(static::$__default, static::class);
        }

        return null;
    }

    /**
     * デフォルト値を持ったインスタンスを取得
     *
     * @return $this
     *
     * @throws \Polass\Enum\Exceptions\ConstantNotFoundException
     */
    public static function default()
    {
        return static::make();
    }

    /**
     * 定数の値を取得
     *
     * @param string $key
     * @param bool $throws
     * @return mixed
     *
     * @throws \Polass\Enum\Exceptions\ConstantNotFoundException
     */
    public static function valueOf($key, $throws = true)
    {
        if (! static::has($key)) {
            if ($throws) {
                throw (new ConstantNotFoundException)->setKey($key, static::class);
            }
            else {
                return null;
            }
        }

        return static::constants()[$key];
    }

    /**
     * 定数のキーを取得
     *
     * @param mixed $value
     * @param bool $throws
     * @return (string|array)
     *
     * @throws \Polass\Enum\Exceptions\ConstantWithValueNotFoundException
     */
    public static function keyOf($value, $throws = true)
    {
        $matches = [];

        foreach (static::constantsWithoutDefault() as $key => $val) {
            if ($val === $value) {
                $matches[] = $key;
            }
        }

        if (($count = count($matches)) === 0) {
            if ($throws) {
                throw (new ConstantWithValueNotFoundException)->setValue($value, static::class);
            }
            else {
                return null;
            }
        }
        elseif ($count === 1) {
            return $matches[0];
        }

        return $matches;
    }

    /**
     * 定数名でメソッド呼び出しされたらインスタンスを返す
     *
     * @param string $key
     * @param array $args
     * @return $this
     *
     * @throws \Polass\Enum\Exceptions\ConstantNotFoundException
     */
    public static function __callStatic($key, $args)
    {
        return static::make($key);
    }
}
