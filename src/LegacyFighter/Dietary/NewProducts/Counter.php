<?php

namespace LegacyFighter\Dietary\NewProducts;

class Counter {
    /**
     * @var int
     */
    private $counter;

    public static function zero(): Counter
    {
        return new Counter(0);
    }

    /**
     * Counter constructor.
     * @param int $counter
     * @throws \Exception
     */
    public function __construct(int $counter)
    {
        if ($this->counter < 0) {
            throw new \Exception("Cannot have negative counter: " . counter);
        }

        $this->counter = $counter;
    }

    /**
     * @return int
     */
    public function getIntValue(): int
    {
        return $this->counter;
    }

    /**
     * @return Counter
     * @throws \Exception
     */
    public function increment(): Counter
    {
        return new Counter($this->counter + 1);
    }

    /**
     * @return Counter
     * @throws \Exception
     */
    public function decrement(): Counter
    {
        return new Counter($this->counter - 1);
    }

    /**
     * @return bool
     */
    public function hasAny(): bool {
        return $this->counter > 0;
    }
}
