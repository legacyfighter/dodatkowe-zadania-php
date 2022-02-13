<?php

namespace Tests\LegacyFighter\Leave;

use PHPUnit\Framework\Error\Deprecated;
use PHPUnit\Framework\Error\Error;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use LegacyFighter\Leave\EmailSender;
use LegacyFighter\Leave\Employee;
use LegacyFighter\Leave\EscalationManager;
use LegacyFighter\Leave\LeaveDatabase;
use LegacyFighter\Leave\LeaveService;
use LegacyFighter\Leave\MessageBus;
use LegacyFighter\Leave\Result;

class LeaveServiceTest extends TestCase
{
    use ProphecyTrait;

    private $ONE = 1;

    /**
     * @test
     */
    public function requests_of_performers_will_be_manually_processed_after_26th_day(): void
    {
        $database = $this->prophesize(LeaveDatabase::class);
        $database->findByEmployeeId($this->ONE)->willReturn(new Employee($this->ONE, "PERFORMER", 10));

        $messageBus = $this->prophesize(MessageBus::class);
        $messageBus->sendEvent(Argument::type('string'))->shouldNotBeCalled();

        $emailSender = $this->prophesize(EmailSender::class);
        $emailSender->send(Argument::type('string'))->shouldNotBeCalled();

        $escalationManager = $this->prophesize(EscalationManager::class);
        $escalationManager->notifyNewPendingRequest($this->ONE)->shouldBeCalled();

        $leaveService = new LeaveService($database->reveal(), $messageBus->reveal(), $emailSender->reveal(), $escalationManager->reveal());

        $result = $leaveService->requestPaidDaysOff(30, $this->ONE);

        $this->assertEquals(Result::MANUAL(), $result);
    }

    /**
     * @test
     */
    public function performers_cannot_get_more_than_45_days(): void
    {
        $database = $this->prophesize(LeaveDatabase::class);
        $database->findByEmployeeId($this->ONE)->willReturn(new Employee($this->ONE, "PERFORMER", 10));

        $messageBus = $this->prophesize(MessageBus::class);
        $emailSender = $this->prophesize(EmailSender::class);
        $escalationManager = $this->prophesize(EscalationManager::class);

        $leaveService = new LeaveService($database->reveal(), $messageBus->reveal(), $emailSender->reveal(), $escalationManager->reveal());

        $result = $leaveService->requestPaidDaysOff(50, $this->ONE);

        $this->assertEquals(Result::DENIED(), $result);
    }

    /**
     * @test
     */
    public function slackers_do_not_get_any_leave(): void
    {
        $database = $this->prophesize(LeaveDatabase::class);
        $database->findByEmployeeId($this->ONE)->willReturn(new Employee($this->ONE, "SLACKER", 10));

        $messageBus = $this->prophesize(MessageBus::class);
        $emailSender = $this->prophesize(EmailSender::class);
        $escalationManager = $this->prophesize(EscalationManager::class);

        $leaveService = new LeaveService($database->reveal(), $messageBus->reveal(), $emailSender->reveal(), $escalationManager->reveal());

        $result = $leaveService->requestPaidDaysOff(1, $this->ONE);

        $this->assertEquals(Result::DENIED(), $result);
    }

    /**
     * @test
     */
    public function slackers_get_a_nice_email(): void
    {
        $database = $this->prophesize(LeaveDatabase::class);
        $database->findByEmployeeId($this->ONE)->willReturn(new Employee($this->ONE, "SLACKER", 10));

        $messageBus = $this->prophesize(MessageBus::class);
        $emailSender = $this->prophesize(EmailSender::class);
        $emailSender->send('next time')->shouldBeCalled();

        $escalationManager = $this->prophesize(EscalationManager::class);

        $leaveService = new LeaveService($database->reveal(), $messageBus->reveal(), $emailSender->reveal(), $escalationManager->reveal());

        $leaveService->requestPaidDaysOff(1, $this->ONE);
    }

    /**
     * @test
     */
    public function regular_employee_doesnt_get_more_than_26_days(): void
    {
        $database = $this->prophesize(LeaveDatabase::class);
        $database->findByEmployeeId($this->ONE)->willReturn(new Employee($this->ONE, "REGULAR", 10));

        $messageBus = $this->prophesize(MessageBus::class);
        $messageBus->sendEvent(Argument::type('string'))->shouldNotBeCalled();

        $emailSender = $this->prophesize(EmailSender::class);
        $emailSender->send('next time')->shouldBeCalled();

        $escalationManager = $this->prophesize(EscalationManager::class);
        $escalationManager->notifyNewPendingRequest($this->ONE)->shouldNotBeCalled();

        $leaveService = new LeaveService($database->reveal(), $messageBus->reveal(), $emailSender->reveal(), $escalationManager->reveal());

        $result = $leaveService->requestPaidDaysOff(20, $this->ONE);

        $this->assertEquals(Result::DENIED(), $result);
    }

    /**
     * @test
     */
    public function regular_employee_gets_26_days(): void
    {
        $regular = new Employee($this->ONE, "REGULAR", 10);

        $database = $this->prophesize(LeaveDatabase::class);
        $database->findByEmployeeId($this->ONE)->willReturn($regular);
        $database->save($regular)->shouldBeCalled();

        $messageBus = $this->prophesize(MessageBus::class);
        $messageBus->sendEvent('request approved')->shouldBeCalled();

        $emailSender = $this->prophesize(EmailSender::class);
        $emailSender->send(Argument::type('string'))->shouldNotBeCalled();

        $escalationManager = $this->prophesize(EscalationManager::class);
        $escalationManager->notifyNewPendingRequest($this->ONE)->shouldNotBeCalled();

        $leaveService = new LeaveService($database->reveal(), $messageBus->reveal(), $emailSender->reveal(), $escalationManager->reveal());

        $result = $leaveService->requestPaidDaysOff(5, $this->ONE);

        $this->assertEquals(Result::APPROVED(), $result);
    }


}
