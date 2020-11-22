<?php

namespace Refactoring\Dietary;

use Brick\Math\BigDecimal;

class OrderLine
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var BigDecimal
     */
    private $price;

    /**
     * @var Order
     */
    private $order;

    /**
     * @var Product
     */
    private $product;

    /**
     * @var int
     */
    private $quantity;

    /**
     * OrderLine constructor.
     */
    public function __construct()
    {
        $this->id = random_int(0, PHP_INT_MAX); // SHORTCUT
    }

    /**
     * @return BigDecimal
     */
    public function getPrice(): BigDecimal
    {
        return $this->price;
    }

    /**
     * @param BigDecimal $price
     */
    public function setPrice(BigDecimal $price): void
    {
        $this->price = $price;
    }

    /**
     * @return Product
     */
    public function getProduct(): Product
    {
        return $this->product;
    }

    /**
     * @param Product $product
     */
    public function setProduct(Product $product): void
    {
        $this->product = $product;
    }

    /**
     * @return int
     */
    public function getQuantity(): int
    {
        return $this->quantity;
    }

    /**
     * @param int $quantity
     */
    public function setQuantity(int $quantity): void
    {
        $this->quantity = $quantity;
    }

    /**
     * @return Order
     */
    public function getOrder(): Order
    {
        return $this->order;
    }

    /**
     * @param Order $order
     */
    public function setOrder(Order $order): void
    {
        $this->order = $order;
    }
}