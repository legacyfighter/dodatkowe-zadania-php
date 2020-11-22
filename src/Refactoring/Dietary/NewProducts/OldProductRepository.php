<?php

namespace Refactoring\Dietary\NewProducts;

use Ramsey\Uuid\UuidInterface;

interface OldProductRepository
{
    public function getOne(UuidInterface $productId): ?OldProduct;

    public function save(OldProduct $product): void;

    function findAll(): array;
}