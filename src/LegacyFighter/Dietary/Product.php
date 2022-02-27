<?php

namespace LegacyFighter\Dietary;

class Product
{
    private $id;

    /**
     * @var string
     */
    private $product;

    /**
     * @var int
     */
    private $counter;

    /**
     * Product constructor.
     */
    public function __construct()
    {
        $this->id = random_int(0, PHP_INT_MAX); // SHORTCUT
    }

    /**
     *
     */
    public function decrementCounter(): void
    {
        $this->counter--;
    }

    /**
     *
     */
    public function incrementCounter(): void
    {
        $this->counter++;
    }
}
