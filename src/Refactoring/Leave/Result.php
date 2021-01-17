<?php

namespace Refactoring\Leave;

use MyCLabs\Enum\Enum;

class Result extends Enum
{
    public const APPROVED = 'approved';
    public const DENIED = 'denied';
    public const MANUAL = 'manual';
}