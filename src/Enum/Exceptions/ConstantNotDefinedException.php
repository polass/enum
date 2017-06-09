<?php

namespace Polass\Enum\Exceptions;

use LogicException;
use Polass\Enum\Enum;

class ConstantNotDefinedException extends LogicException
{
    /**
     * 定数を定義すべき Enum クラスの名前
     *
     * @var string
     */
    protected $enum;

    /**
     * 定義されるべき定数の名前
     *
     * @var string
     */
    protected $constant;

    /**
     * 定数を定義すべき Enum クラスの名前と定数の名前を設定
     *
     * @param  string  $constant
     * @param  string  $enum
     * @return $this
     */
    public function setConstant($constant, $enum = null)
    {
        $this->constant = $constant;
        $this->enum = ($enum !== null) ? $enum : Enum::class;

        $this->message = "Constant [{$this->constant}] does not defined on [{$this->enum}].";

        return $this;
    }

    /**
     * 定義されるべき定数の名前を取得
     *
     * @return string
     */
    public function getConstant()
    {
        return $this->constant;
    }

    /**
     * 定数を定義すべき Enum クラスの名前を取得
     *
     * @return string
     */
    public function getEnum()
    {
        return $this->enum;
    }
}
