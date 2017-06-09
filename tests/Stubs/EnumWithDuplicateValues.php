<?php

namespace Tests\Stubs;

use Polass\Enum\Enum;

class EnumWithDuplicateValues extends Enum
{
    /**
     * @var string
     */
    const FOO = 'duplicated';

    /**
     * @var string
     */
    const BAR = 'duplicated';

    /**
     * @var string
     */
    const BAZ = 'single';
}
