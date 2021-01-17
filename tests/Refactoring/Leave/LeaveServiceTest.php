<?php

namespace Tests\Refactoring\Leave;

use PHPUnit\Framework\Error\Deprecated;
use PHPUnit\Framework\Error\Error;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Refactoring\Leave\EmailSender;
use Refactoring\Leave\EscalationManager;
use Refactoring\Leave\LeaveDatabase;
use Refactoring\Leave\LeaveService;
use Refactoring\Leave\MessageBus;
use Refactoring\Leave\Result;

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
        $database->findByEmployeeId($this->ONE)->willReturn(["PERFORMER", 10]);

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
        $database->findByEmployeeId($this->ONE)->willReturn(["SLACKER", 10]);

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
        $database->findByEmployeeId($this->ONE)->willReturn(["SLACKER", 10]);

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
        $database->findByEmployeeId($this->ONE)->willReturn(["SLACKER", 10]);

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
        $database->findByEmployeeId($this->ONE)->willReturn(["REGULAR", 10]);

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
        $database = $this->prophesize(LeaveDatabase::class);
        $database->findByEmployeeId($this->ONE)->willReturn(["REGULAR", 10]);
        $database->save(["REGULAR", 15])->shouldBeCalled();

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