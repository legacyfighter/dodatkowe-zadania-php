<?php

namespace Tests\Refactoring\Dietary\NewProducts;

use Brick\Math\BigDecimal;
use PHPUnit\Framework\TestCase;
use Refactoring\Dietary\NewProducts\OldProduct;
use Refactoring\Dietary\NewProducts\OldProductDescription;

class OldProductDescriptionTest extends TestCase
{
    /**
     * @test
     */
    public function anFormatDescription(): void
    {
        //expect
        $this->assertEquals("short *** long", $this->productWithDesc("short", "long")->formatDesc());
        $this->assertEquals("", $this->productWithDesc("short", "")->formatDesc());
        $this->assertEquals("", $this->productWithDesc("", "long2")->formatDesc());
    }

    /**
     * @test
     */
    public function canChangeCharInDescription() {
        //given
        $p = $this->productWithDesc("short", "long");

        //when
        $p->replaceCharFromDesc('s', 'z');

        //expect
        $this->assertEquals("zhort *** long", $p->formatDesc());
    }

    /**
     * @param string $desc
     * @param string $longDesc
     * @return OldProductDescription@
     */
    private function productWithDesc(string $desc, string $longDesc) {
        return new OldProductDescription(new OldProduct(BigDecimal::ten(), $desc, $longDesc, 10));
    }

}