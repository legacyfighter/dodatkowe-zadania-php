<?php

namespace Refactoring\Dietary\NewProducts;

use phpstream\collectors\ArrayCollector;
use phpstream\impl\MemoryStream;
use Ramsey\Uuid\UuidInterface;

class OldProductDescriptionRepository
{
    /**
     * @var OldProductRepository
     */
    private $oldProductRepository;

    /**
     * OldProductDescriptionRepository constructor.
     * @param OldProductRepository $oldProductRepository
     */
    public function __construct(OldProductRepository $oldProductRepository)
    {
        $this->oldProductRepository = $oldProductRepository;
    }

    /**
     * @return array
     */
    public function findAll(): array
    {
        return (new MemoryStream($this->oldProductRepository->findAll()))
            ->map(function (OldProduct $p) {
                return new OldProductDescription($p);
            })
            ->collect(new ArrayCollector());
    }

    /**
     * @param UuidInterface $productId
     * @return OldProductDescription
     */
    public function getOne(UuidInterface $productId): OldProductDescription
    {
        return new OldProductDescription($this->oldProductRepository->getOne($productId));
    }
}