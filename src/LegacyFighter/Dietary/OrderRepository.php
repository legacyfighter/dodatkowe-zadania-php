<?php

namespace LegacyFighter\Dietary;

interface OrderRepository
{
    public function findByOrderState(OrderState $state): array;

    public function save(Order $order): Order;

    public function getOne(int $orderId): Order;
}
