<?php

namespace Polass\Tests\Stubs;

use Polass\Enum\Enum;

class EnumWithoutDefault extends Enum
{
    /**
     * @var string
     */
    const FOO = 'foo';

    /**
     * @var string
     */
    const BAR = 'bar';
}
