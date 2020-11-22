<?php

namespace Refactoring\Dietary\NewProducts;

use Brick\Math\BigDecimal;
use Ramsey\Uuid\UuidInterface;

class OldProductService
{
    /**
     * @var OldProductRepository
     */
    private $oldProductRepository;

    /**
     * OldProductService constructor.
     * @param OldProductRepository $oldProductRepository
     */
    public function __construct(OldProductRepository $oldProductRepository)
    {
        $this->oldProductRepository = $oldProductRepository;
    }

    /**
     * @param UuidInterface $productId
     * @param string $oldChar
     * @param string $newChar
     * @throws \Exception
     */
    public function replaceCharInDesc(UuidInterface $productId, string $oldChar, string $newChar) {
        $product = $this->oldProductRepository->getOne($productId);

        $product->replaceCharFromDesc($oldChar, $newChar);

        $this->oldProductRepository->save($product);
    }

    /**
     * @param UuidInterface $productId
     * @throws \Exception
     */
    public function incrementCounter(UuidInterface $productId): void
    {
        $product = $this->oldProductRepository->getOne($productId);

        $product->incrementCounter();

        $this->oldProductRepository->save($product);
    }

    /**
     * @param UuidInterface $productId
     * @param BigDecimal $newPrice
     * @throws \Exception
     */
    public function changePriceOf(UuidInterface $productId, BigDecimal $newPrice): void
    {
        $product = $this->oldProductRepository->getOne($productId);

        $product->changePriceTo($newPrice);

        $this->oldProductRepository->save($product);
    }

    /**
     * @param UuidInterface $serialNumber
     * @return int
     */
    public function getCounterOf(UuidInterface $serialNumber): int
    {
        return $this->oldProductRepository->getOne($serialNumber)->getCounter();
    }

    /**
     * @param UuidInterface $serialNumber
     * @return BigDecimal
     */
    public function getPriceOf(UuidInterface $serialNumber): BigDecimal {
        return $this->oldProductRepository->getOne($serialNumber)->getPrice();
    }
}