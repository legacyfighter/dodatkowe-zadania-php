<?php

namespace LegacyFighter\Dietary\NewProducts;

use Brick\Math\BigDecimal;
use phpstream\collectors\ArrayCollector;
use phpstream\impl\MemoryStream;
use Ramsey\Uuid\UuidInterface;

class OldProductService
{
    /**
     * @var OldProductRepository
     */
    private $oldProductRepository;

    /**
     * @var OldProductDescriptionRepository
     */
    private $oldProductDescriptionRepository;

    /**
     * OldProductService constructor.
     * @param OldProductRepository $oldProductRepository
     * @param OldProductDescriptionRepository $oldProductDescriptionRepository
     */
    public function __construct(OldProductRepository $oldProductRepository, OldProductDescriptionRepository $oldProductDescriptionRepository)
    {
        $this->oldProductRepository = $oldProductRepository;
        $this->oldProductDescriptionRepository = $oldProductDescriptionRepository;
    }

    /**
     * @return array
     */
    public function findAllDescriptions(): array
    {
        return (new MemoryStream($this->oldProductDescriptionRepository->findAll()))
            ->map(function(OldProductDescription $product) {
                return $product->formatDesc();
            })
            ->collect(new ArrayCollector());
    }

    /**
     * @param UuidInterface $productId
     * @param string $oldChar
     * @param string $newChar
     * @throws \Exception
     */
    public function replaceCharInDesc(UuidInterface $productId, string $oldChar, string $newChar) {
        $product = $this->oldProductDescriptionRepository->getOne($productId);

        $product->replaceCharFromDesc($oldChar, $newChar);

        //$this->oldProductRepository->save($product);
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
     * @throws \Exception
     */
    public function decrementCounter(UuidInterface $productId): void
    {
        $product = $this->oldProductRepository->getOne($productId);

        $product->decrementCounter();

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
