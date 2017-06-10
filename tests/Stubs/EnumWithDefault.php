<?php

namespace Polass\Tests\Stubs;

use Polass\Enum\Enum;

class EnumWithDefault extends Enum
{
    /**
     * @var string
     */
    const __default = 'FOO';

    /**
     * @var string
     */
    const FOO = 'foo';

    /**
     * @var string
     */
    const BAR = 'bar';
}
