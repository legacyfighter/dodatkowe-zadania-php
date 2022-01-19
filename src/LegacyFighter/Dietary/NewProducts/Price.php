<?php

namespace LegacyFighter\Dietary\NewProducts;

use Brick\Math\BigDecimal;

class Price
{
    /**
     * @var BigDecimal
     */
    private $price;

    /**
     * Price constructor.
     * @param BigDecimal $price
     * @throws \Exception
     */
    private function __construct(?BigDecimal $price)
    {
        if ($price === null || $price->getSign() < 0) {
            throw new \Exception("Cannot have this price");
        }

        $this->price = $price;
    }

    /**
     * @param BigDecimal $value
     * @return Price
     * @throws \Exception
     */
    public static function of(?BigDecimal $value): Price
    {
        return new Price($value);
    }

    /**
     * @return bool
     */
    public function isNotZero(): bool
    {
        return $this->price->getSign() != 0;
    }

    /**
     * @return BigDecimal
     */
    public function getAsBigDecimal(): BigDecimal {
        return $this->price;
    }
}
