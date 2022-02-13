<?php

namespace Tests\LegacyFighter\Dietary\NewProducts;

use Brick\Math\BigDecimal;
use PHPUnit\Framework\TestCase;
use LegacyFighter\Dietary\NewProducts\OldProduct;
use LegacyFighter\Dietary\NewProducts\OldProductDescriptionRepository;
use LegacyFighter\Dietary\NewProducts\OldProductRepository;
use LegacyFighter\Dietary\NewProducts\OldProductService;

class OldProductServiceTest extends TestCase
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
     * @var OldProductService;
     */
    private $oldProductService;

    /**
     * @test
     */
    public function canListAllProductsDecsriptions(): void
    {
        //given
        $this->oldProductRepository->save($this->productWithDesc("desc1", "longDesc1"));
        $this->oldProductRepository->save($this->productWithDesc("desc2", "longDesc2"));

        //when
        $allDescriptions = $this->oldProductService->findAllDescriptions();

        //then
        $this->assertContains("desc1 *** longDesc1", $allDescriptions);
        $this->assertContains("desc2 *** longDesc2", $allDescriptions);
    }

    /**
     * @test
     */
    public function canDecrementCounter(): void
    {
        //given
        $oldProduct = $this->oldProductRepository->save($this->productWithPriceAndCounter(BigDecimal::ten(), 10));

        //when
        $this->oldProductService->decrementCounter($oldProduct->serialNumber());

        //then
        $this->assertEquals(9, $this->oldProductService->getCounterOf($oldProduct->serialNumber()));
    }

    /**
     * @test
     */
    public function canIncrementCounter(): void
    {
        //given
        $oldProduct = $this->oldProductRepository->save($this->productWithPriceAndCounter(BigDecimal::ten(), 10));

        //when
        $this->oldProductService->incrementCounter($oldProduct->serialNumber());

        //then
        $this->assertEquals(11, $this->oldProductService->getCounterOf($oldProduct->serialNumber()));
    }

    /**
     * @test
     */
    public function canChangePrice(): void
    {
        //given
        $oldProduct = $this->oldProductRepository->save($this->productWithPriceAndCounter(BigDecimal::ten(), 10));

        //when
        $this->oldProductService->changePriceOf($oldProduct->serialNumber(), BigDecimal::zero());

        //then
        $this->assertEquals(BigDecimal::zero(), $this->oldProductService->getPriceOf($oldProduct->serialNumber()));
    }


    /**
     * @param BigDecimal $price
     * @param int $counter
     * @return OldProduct
     */
    private function productWithPriceAndCounter(BigDecimal $price, int $counter): OldProduct
    {
        return new OldProduct($price, "desc", "longDesc", $counter);
    }

    /**
     * @param string $desc
     * @param string $longDesc
     * @return OldProduct
     */
    private function productWithDesc(string $desc, string $longDesc): OldProduct
    {
        return new OldProduct(BigDecimal::ten(), $desc, $longDesc, 10);
    }

    /**
     *
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->oldProductRepository = new OldProductRepository\InMemory();
        $this->oldProductDescriptionRepository = new OldProductDescriptionRepository($this->oldProductRepository);
        $this->oldProductService = new OldProductService($this->oldProductRepository, $this->oldProductDescriptionRepository);
    }
}
