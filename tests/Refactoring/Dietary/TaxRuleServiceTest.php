<?php

namespace Tests\Refactoring\Dietary;

use PHPUnit\Framework\TestCase;
use Refactoring\Dietary\OrderRepository;
use Refactoring\Dietary\Repository\InMemoryOrderRepository;
use Refactoring\Dietary\Repository\InMemoryTaxConfigRepository;
use Refactoring\Dietary\Repository\InMemoryTaxRuleRepository;
use Refactoring\Dietary\TaxConfig;
use Refactoring\Dietary\TaxConfigRepository;
use Refactoring\Dietary\TaxRule;
use Refactoring\Dietary\TaxRuleRepository;
use Refactoring\Dietary\TaxRuleService;

class TaxRuleServiceTest extends TestCase
{
    /**
     * @var TaxRuleService
     */
    private $service;

    /**
     * @var TaxConfigRepository
     */
    private $taxConfigRepository;

    /**
     * @var TaxRuleRepository
     */
    private $taxRuleRepository;

    /**
     * @var OrderRepository
     */
    private $orderRepository;

    /**
     * @test
     * @dataProvider getInvalidCountryCodes
     * @param string $countryCode
     * @throws \Exception
     */
    public function countryCodeIsAlwaysValid(string $countryCode): void
    {
        $rule = TaxRule::linearRule(10, 10, "taxCode");

        $this->expectException(\Exception::class);

        $this->createConfigWithInitialRule($countryCode, 2, $rule);
    }

    /**
     * @return string[]
     */
    public function getInvalidCountryCodes(): array
    {
        return [[""], ["1"]];
    }

    /**
     * @test
     */
    public function aFactorIsNotZero(): void
    {
        //given
        $this->createConfigWithInitialRule("HUN", 2, TaxRule::linearRule(10, 10, "taxCode"));

        //expect
        $this->expectException(\Exception::class);

        //when
        $this->service->addTaxRuleToCountry("HUN", 0, 4, "taxRule2");
    }

    /**
     * @test
     */
    public function aFactorIsNotZeroWithcFactor(): void
    {
        //given
        $this->createConfigWithInitialRule("HUN", 2, TaxRule::linearRule(10, 10, "taxCode"));

        //expect
        $this->expectException(\Exception::class);

        //when
        $this->service->addTaxRuleToCountry2("HUN", 0, 4, 5, "taxRule2");
    }

    /**
     * @test
     */
    public function shouldNotHaveMoreThanMaximumNumberOfRules(): void
    {
        //given
        $rule = TaxRule::linearRule(10, 10, "taxCode");
        $config = $this->createConfigWithInitialRule("PL1", 2, $rule);
        //and
        $this->service->addTaxRuleToCountry("PL1", 2, 4, "taxRule2");

        //expect
        $this->expectException(\Exception::class);

        //when
        $this->service->addTaxRuleToCountry("PL1", 2, 4, "taxRule3");
    }

    /**
     * @test
     */
    public function canAddARule(): void
    {
        //given
        $rule = TaxRule::linearRule(10, 10, "taxCode");
        $config = $this->createConfigWithInitialRule("PL2", 2, $rule);

        //when
        $this->service->addTaxRuleToCountry("PL2", 2, 4, "taxRule2");

        //then
        $this->assertEquals(2, $this->service->rulesCount("PL2"));
    }

    /**
     * @test
     */
    public function canDeleteARule(): void
    {
        //given
        $rule = TaxRule::linearRule(10, 10, "taxCode");
        $config = $this->createConfigWithInitialRule("PL3", 2, $rule);
        //and
        $this->service->addTaxRuleToCountry("PL3", 2, 4, "taxRule2");

        //when
        $this->service->deleteRule($rule->getId(), $config->getId());

        //expect
        $this->assertEquals(1, $this->service->rulesCount("PL3"));
    }

    /**
     * @test
     */
    public function cannotDeleteARuleIfThatIsTheLastOne(): void
    {
        //given
        $rule = TaxRule::linearRule(10, 10, "taxCode");
        $config = $this->createConfigWithInitialRule("PL4", 2, $rule);

        //expect
        $this->expectException(\Exception::class);

        //when
        $this->service->deleteRule($rule->getId(), $config->getId());
    }


    /**
     *
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->taxConfigRepository = new InMemoryTaxConfigRepository();
        $this->taxRuleRepository = new InMemoryTaxRuleRepository($this->taxConfigRepository);
        $this->orderRepository = new InMemoryOrderRepository();
        $this->service = new TaxRuleService($this->taxRuleRepository, $this->taxConfigRepository, $this->orderRepository);
    }

    /**
     * @param string $countryCode
     * @param int $maxRules
     * @param TaxRule $rule
     * @return TaxConfig
     * @throws \Exception
     */
    private function createConfigWithInitialRule(string $countryCode, int $maxRules, TaxRule $rule): TaxConfig
    {
        return $this->service->createTaxConfigWithRuleAndMaxCount($countryCode, $maxRules, $rule);
    }

}