<?php

namespace Refactoring\Dietary\Repository;

use phpstream\collectors\ArrayCollector;
use phpstream\impl\MemoryStream;
use Refactoring\Dietary\Order;
use Refactoring\Dietary\OrderRepository;
use Refactoring\Dietary\OrderState;

class InMemoryOrderRepository implements OrderRepository
{
    /**
     * @var Order[]
     */
    private $orders = [];

    /**
     * @param OrderState $state
     * @return array
     */
    public function findByOrderState(OrderState $state): array
    {
        return (new MemoryStream($this->orders))
            ->filter(function (Order $order) use ($state) {
                return $order->getOrderState()->equals($state);
            })
            ->collect(new ArrayCollector());
    }

    /**
     * @param Order $order
     * @return Order
     */
    public function save(Order $order): Order
    {
        $this->orders[$order->getId()] = $order;

        return $order;
    }

    /**
     * @param int $orderId
     * @return Order
     */
    public function getOne(int $orderId): Order
    {
        return $this->orders[$orderId];
    }
}