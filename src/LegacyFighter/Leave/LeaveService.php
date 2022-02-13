<?php

namespace LegacyFighter\Leave;

class LeaveService
{
    /**
     * @var LeaveDatabase
     */
    private $database;

    /**
     * @var MessageBus
     */
    private $messageBus;

    /**
     * @var EmailSender
     */
    private $emailSender;

    /**
     * @var EscalationManager
     */
    private $escalationManager;

    /**
     * LeaveService constructor.
     * @param LeaveDatabase $database
     * @param MessageBus $messageBus
     * @param EmailSender $emailSender
     * @param EscalationManager $escalationManager
     */
    public function __construct(LeaveDatabase $database, MessageBus $messageBus, EmailSender $emailSender, EscalationManager $escalationManager)
    {
        $this->database = $database;
        $this->messageBus = $messageBus;
        $this->emailSender = $emailSender;
        $this->escalationManager = $escalationManager;
    }

    public function requestPaidDaysOff(int $days, int $employeeId): Result
    {
        if ($days < 0) {
            throw new \InvalidArgumentException();
        }

        $result = null;

        $employeeData = $this->database->findByEmployeeId($employeeId);

        $employeeStatus = (string)$employeeData[0];
        $daysSoFar = (int)$employeeData[1];

        if ($daysSoFar + $days > 26) {

            if ($employeeStatus == "PERFORMER" && $daysSoFar + $days < 45) {
                $result = Result::MANUAL();
                $this->escalationManager->notifyNewPendingRequest($employeeId);
            } else {
                $result = Result::DENIED();
                $this->emailSender->send("next time");
            }

        } else {

            if ($employeeStatus == "SLACKER") {
                $result = Result::DENIED();
                $this->emailSender->send("next time");
            } else {
                $employeeData[1] = $daysSoFar + $days;
                $result = Result::APPROVED();
                $this->database->save($employeeData);
                $this->messageBus->sendEvent("request approved");
            }
        }

        return $result;
    }
}
