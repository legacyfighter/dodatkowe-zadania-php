<?php

namespace Refactoring\Dietary;

use Cassandra\Date;
use Munus\Collection\GenericList;

class Order
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var OrderType
     */
    private $orderType;

    /**
     * @var OrderState
     */
    private $orderState;

    /**
     * @var \DateTime
     */
    private $confirmationTimestamp;

    /**
     * @var GenericList
     */
    private $items;

    /**
     * Order constructor.
     */
    public function __construct()
    {
        $this->id = random_int(0, PHP_INT_MAX); // SHORTCUT
        $this->confirmationTimestamp = new \DateTime();
        $this->items = GenericList::empty();
    }

    /**
     * @return OrderType
     */
    public function getOrderType(): OrderType
    {
        return $this->orderType;
    }

    /**
     * @param OrderType $orderType
     */
    public function setOrderType(OrderType $orderType): void
    {
        $this->orderType = $orderType;
    }

    /**
     * @return OrderState
     */
    public function getOrderState(): OrderState
    {
        return $this->orderState;
    }

    /**
     * @param OrderState $orderState
     */
    public function setOrderState(OrderState $orderState): void
    {
        $this->orderState = $orderState;
    }

    /**
     * @return \DateTime
     */
    public function getConfirmationTimestamp(): \DateTime
    {
        return $this->confirmationTimestamp;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }
}