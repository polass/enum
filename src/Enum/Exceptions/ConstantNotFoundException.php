<?php

namespace Polass\Enum\Exceptions;

use RuntimeException;

class ConstantNotFoundException extends RuntimeException
{
    /**
     * 定数を探した Enum クラスの名前
     *
     * @var string
     */
    protected $enum;

    /**
     * 探した定数の名前
     *
     * @var string
     */
    protected $key;

    /**
     * 定数を探した Enum クラスの名前と定数の名前を設定
     *
     * @param  string  $key
     * @param  string  $enum
     * @return $this
     */
    public function setKey($key, $enum = null)
    {
        $this->key = $key;
        $this->enum = ($enum !== null) ? $enum : Enum::class;

        $this->message = "Constant `{$this->key}` does not defined on [{$this->enum}].";

        return $this;
    }

    /**
     * 探した定数の名前を取得
     *
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * 定数を探した Enum クラスの名前を取得
     *
     * @return string
     */
    public function getEnum()
    {
        return $this->enum;
    }
}
