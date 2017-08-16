<?php

namespace Polass\Enum\Traits;

use ReflectionClass;

trait HasConstants
{
    /**
     * 定数のキャッシュ
     *
     * @var array
     */
    private static $_constants = [];

    /**
     * 継承した Enum クラスが持つ定数の値を定数名をキーとして配列で取得
     *
     * @return array
     */
    public static function constants()
    {
        $class = get_called_class();

        if (! array_key_exists($class, self::$_constants)) {
            self::$_constants[$class] = (new ReflectionClass($class))->getConstants();
        }

        return self::$_constants[$class];
    }
}
