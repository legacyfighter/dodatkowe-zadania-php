<?php

namespace LegacyFighter\Dietary\NewProducts\OldProductRepository;

use Ramsey\Uuid\UuidInterface;
use LegacyFighter\Dietary\NewProducts\OldProduct;
use LegacyFighter\Dietary\NewProducts\OldProductRepository;

class InMemory implements OldProductRepository
{
    /**
     * @var array
     */
    private $products = [];

    /**
     * @param UuidInterface $productId
     * @return OldProduct
     */
    public function getOne(UuidInterface $productId): ?OldProduct
    {
        if (!array_key_exists($productId->toString(), $this->products)) {
            return null;
        }

        return $this->products[$productId->toString()];
    }

    /**
     * @param OldProduct $product
     */
    public function save(OldProduct $product): OldProduct
    {
        $this->products[$product->serialNumber()->toString()] = $product;

        return $product;
    }

    /**
     * @return array
     */
    function findAll(): array
    {
        return array_values($this->products);
    }
}
