<?php

namespace Tests\Refactoring\Dietary\NewProducts;

use Brick\Math\BigDecimal;
use PHPUnit\Framework\TestCase;
use Refactoring\Dietary\NewProducts\OldProduct;
use Refactoring\Dietary\NewProducts\OldProductRepository;
use Refactoring\Dietary\NewProducts\OldProductService;

class OldProductServiceTest extends TestCase
{
    /**
     * @var OldProductRepository
     */
    private $oldProductRepository;

    /**
     * @var OldProductService;
     */
    private $oldProductService;

    /**
     * @test
     */
    public function canIncrementCounterIfPriceIsPositive(): void
    {
        //given
        $p = $this->productWithPriceAndCounter(BigDecimal::ten(), 10);

        //when
        $this->oldProductService->incrementCounter($p->getId());

        //then
        $this->assertEquals(11, $this->oldProductService->getCounterOf($p->getId()));
    }

    /**
     * @test
     */
    public function cannotIncrementCounterIfPriceIsNotPositive(): void
    {
        //given
        $p = $this->productWithPriceAndCounter(BigDecimal::zero(), 10);

        //expect
        $this->expectException(\Exception::class);

        // when
        $this->oldProductService->incrementCounter($p->getId());
    }

    /**
     * @test
     */
    public function canDecrementCounterIfPriceIsPositive(): void
    {
        //given
        $p = $this->productWithPriceAndCounter(BigDecimal::ten(), 10);

        //when
        $this->oldProductService->decrementCounter($p->getId());

        //then
        $this->assertEquals(9, $this->oldProductService->getCounterOf($p->getId()));
    }

    /**
     * @test
     */
    public function cannotDecrementCounterIfPriceIsNotPositive(): void
    {
        //given
        $p = $this->productWithPriceAndCounter(BigDecimal::zero(), 0);

        //expect
        $this->expectException(\Exception::class);

        //when
        $this->oldProductService->decrementCounter($p->getId());
    }

    /**
     * @test
     */
    public function canChangePriceIfCounterIsPositive() {
        //given
        $p = $this->productWithPriceAndCounter(BigDecimal::ten(), 10);


        //when
        $this->oldProductService->changePriceOf($p->getId(), BigDecimal::of(3));

        //then
        $this->assertEquals(BigDecimal::of(3), $this->oldProductService->getPriceOf($p->getId()));
    }

    /**
     * @test
     */
    public function cannotChangePriceIfCounterIsNotPositive(): void
    {
        //given
        $p = $this->productWithPriceAndCounter(BigDecimal::zero(), 0);

        //when
        $this->oldProductService->changePriceOf($p->getId(), BigDecimal::ten());

        //then
        $this->assertEquals(BigDecimal::zero(), $this->oldProductService->getPriceOf($p->getId()));
    }

    /**
     * @test
     */
    public function canFormatDescription() {
        //given
        $this->productWithDesc("short", "long");

        //then
        $this->assertContains("short *** long", $this->oldProductService->findAllDescriptions());
    }

    /**
     * @test
     */
    public function canChangeCharInDescription() {
        //given
        $p = $this->productWithDesc("short", "long");

        //when
        $this->oldProductService->replaceCharInDesc($p->getId(), 'o', '0');

        //then
        $this->assertContains("sh0rt *** l0ng", $this->oldProductService->findAllDescriptions());
    }

    /**
     * @param BigDecimal $price
     * @param int $counter
     * @return OldProduct
     */
    private function productWithPriceAndCounter(BigDecimal $price, int $counter): OldProduct
    {
        return $this->oldProductRepository->save(new OldProduct($price, "desc", "longDesc", $counter));
    }

    /**
     * @param string $desc
     * @param string $longDesc
     * @return OldProduct
     */
    private function productWithDesc(string $desc, string $longDesc): OldProduct
    {
        return $this->oldProductRepository->save(new OldProduct(BigDecimal::ten(), $desc, $longDesc, 10));
    }

    /**
     *
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->oldProductRepository = new OldProductRepository\InMemory();
        $this->oldProductService = new OldProductService($this->oldProductRepository);
    }
}