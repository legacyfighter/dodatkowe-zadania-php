<?php

namespace Refactoring\Dietary\NewProducts;

class OldProductDescription
{
    /**
     * @var OldProduct
     */
    private $oldProduct;

    /**
     * OldProductDescription constructor.
     * @param OldProduct $oldProduct
     */
    public function __construct(OldProduct $oldProduct)
    {
        $this->oldProduct = $oldProduct;
    }

    /**
     * @param string $charToReplace
     * @param string $replaceWith
     * @throws \Exception
     */
    public function replaceCharFromDesc(string $charToReplace, string $replaceWith): void
    {
        $this->oldProduct->replaceCharFromDesc($charToReplace, $replaceWith);
    }

    /**
     * @return string
     */
    public function formatDesc(): string
    {
        return $this->oldProduct->formatDesc();
    }
}