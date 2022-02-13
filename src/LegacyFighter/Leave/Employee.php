<?php

namespace LegacyFighter\Leave;

class Employee
{
    /**
     * @var int
     */
    private $employeeId;

    /**
     * @var string
     */
    private $employeeStatus;

    /**
     * @var int
     */
    private $daysSoFar;

    /**
     * Employee constructor.
     * @param int $employeeId
     * @param string $employeeStatus
     * @param int $daysSoFar
     */
    public function __construct(int $employeeId, string $employeeStatus, int $daysSoFar)
    {
        $this->employeeId = $employeeId;
        $this->employeeStatus = $employeeStatus;
        $this->daysSoFar = $daysSoFar;
    }

    /**
     * @param int $days
     * @return Result
     */
    public function requestDaysOff(int $days): Result
    {
        if ($this->daysSoFar + $days > 26) {
            if ($this->employeeStatus == "PERFORMER" && $this->daysSoFar + $days < 45) {
                return Result::MANUAL();
            } else {
                return Result::DENIED();
            }
        } else {
            if ($this->employeeStatus == "SLACKER") {
                return Result::DENIED();
            } else {
                $this->daysSoFar = $this->daysSoFar + $days;
                return Result::APPROVED();
            }
        }
    }
}
