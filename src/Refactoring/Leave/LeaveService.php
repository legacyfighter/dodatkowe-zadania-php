<?php

namespace Refactoring\Leave;

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

        $employee = $this->database->findByEmployeeId($employeeId);
        $result = $employee->requestDaysOff($days);

        if ($result->equals(Result::MANUAL())) {
            $this->escalationManager->notifyNewPendingRequest($employeeId);
        }

        if ($result->equals(Result::DENIED())) {
            $this->emailSender->send("next time");
        }

        if ($result->equals(Result::APPROVED())) {
            $this->messageBus->sendEvent("request approved");
            $this->database->save($employee);

        }

        return $result;
    }
}