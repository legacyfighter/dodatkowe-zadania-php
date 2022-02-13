<?php

namespace LegacyFighter\Leave;

class Configuration
{
    public function getMaxDaysForPerformers(): int
    {
        return 45;
    }

    public function getMaxDays(): int
    {
        return 26;
    }
}
