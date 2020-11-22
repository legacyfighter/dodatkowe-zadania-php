<?php

namespace Refactoring\Dietary;

use MyCLabs\Enum\Enum;

class OrderType extends Enum
{
    public const PHONE = 'phone';
    public const WIRE = 'wire';
    public const WIRE_ONE_ITEM = 'wire_one_item';
    public const SPECIAL_DISCOUNT = 'special_discount';
    public const REGULAR_BATCH = 'regular_batch';
}