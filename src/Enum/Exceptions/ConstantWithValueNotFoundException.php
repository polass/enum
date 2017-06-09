<?php

namespace Polass\Enum\Exceptions;

use RuntimeException;

class ConstantWithValueNotFoundException extends RuntimeException
{
    /**
     * 定数を探した Enum クラスの名前
     *
     * @var string
     */
    protected $enum;

    /**
     * 探した定数の値
     *
     * @var mixed
     */
    protected $value;

    /**
     * 定数を探した Enum クラスの名前と定数の値を設定
     *
     * @param  mixed  $value
     * @param  string  $enum
     * @return $this
     */
    public function setValue($value, $enum = null)
    {
        $this->value = $value;
        $this->enum = ($enum !== null) ? $enum : Enum::class;

        $this->message = "Constant with `{$this->value}` does not defined on [{$this->enum}].";

        return $this;
    }

    /**
     * 探した定数の値を取得
     *
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
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
