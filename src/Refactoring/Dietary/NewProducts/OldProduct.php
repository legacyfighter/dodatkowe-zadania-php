<?php

namespace Refactoring\Dietary\NewProducts;

use Brick\Math\BigDecimal;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class OldProduct
{
    /**
     * @var UuidInterface
     */
    private $serialNumber;

    /**
     * @var Price
     */
    private $price;

    /**
     * @var Description
     */
    private $desc;

    /**
     * @var Counter
     */
    private $counter;

    /**
     * OldProduct constructor.
     * @param BigDecimal|null $price
     * @param string|null $desc
     * @param string|null $longDesc
     * @param int|null $counter
     */
    public function __construct(?BigDecimal $price, ?string $desc, ?string $longDesc, ?int $counter)
    {
        $this->serialNumber = Uuid::uuid4();
        $this->price = Price::of($price);
        $this->desc = new Description($desc, $longDesc);
        $this->counter = new Counter($counter);
    }

    /**
     * @throws \Exception
     */
    public function decrementCounter(): void
    {
        if ($this->price->isNotZero()) {
            $this->counter = $this->counter->decrement();
        } else {
            throw new \Exception("price is zero");
        }
    }

    /**
     * @throws \Exception
     */
    public function incrementCounter(): void
    {
        if ($this->price->isNotZero()) {
            $this->counter = $this->counter->increment();
        } else {
            throw new \Exception("price is zero");
        }

    }

    /**
     * @param BigDecimal|null $newPrice
     * @throws \Exception
     */
    public function changePriceTo(?BigDecimal $newPrice): void
    {
        if ($this->counter->hasAny()) {
            $this->price = Price::of($price);
        }
    }

    /**
     * @param string|null $charToReplace
     * @param string|null $replaceWith
     * @throws \Exception
     */
    public function replaceCharFromDesc(?string $charToReplace, ?string $replaceWith): void
    {
        $this->desc = $this->desc->replace($charToReplace, $replaceWith);
    }

    /**
     * @return string
     */
    public function formatDesc(): string
    {
        return $this->desc->formatted();
    }

    /**
     * @return BigDecimal
     */
    public function getPrice(): BigDecimal
    {
        return $this->price->getAsBigDecimal();
    }

    /**
     * @return int
     */
    public function getCounter(): int
    {
        return $this->counter->getIntValue();
    }

    /**
     * @return UuidInterface
     */
    public function serialNumber(): UuidInterface
    {
        return $this->serialNumber;
    }
}





















