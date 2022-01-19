<?php

namespace LegacyFighter\Dietary;

use MyCLabs\Enum\Enum;

class OrderState extends Enum
{
    public const INITIAL = 'initial';
    public const PAID = 'paid';
    public const DELIVERED = 'delivered';
    public const RETURNED = 'returned';
}
