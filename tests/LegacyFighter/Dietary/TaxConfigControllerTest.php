<?php

namespace Tests\LegacyFighter\Dietary;

use LegacyFighter\Dietary\Repository\InMemoryOrderRepository;
use LegacyFighter\Dietary\Repository\InMemoryTaxConfigRepository;
use LegacyFighter\Dietary\Repository\InMemoryTaxRuleRepository;
use LegacyFighter\Dietary\TaxConfig;
use LegacyFighter\Dietary\TaxConfigController;
use LegacyFighter\Dietary\TaxConfigRepository;
use LegacyFighter\Dietary\TaxRule;
use LegacyFighter\Dietary\TaxRuleService;
use Munus\Collection\GenericList;
use PHPUnit\Framework\TestCase;

final class TaxConfigControllerTest extends TestCase
{
    private TaxConfigController $taxConfigController;
    private TaxConfigRepository $taxConfigRepository;
    private TaxRuleService $taxRuleService;

    private string $countryCode = '_country-code';
    private string $countryCode2 = '_country-code2';

    protected function setUp(): void
    {
        $this->taxConfigRepository = new InMemoryTaxConfigRepository();
        $this->taxRuleService = new TaxRuleService(
            new InMemoryTaxRuleRepository($this->taxConfigRepository),
            $this->taxConfigRepository,
            new InMemoryOrderRepository()
        );
        $this->taxConfigController = new TaxConfigController($this->taxRuleService);
    }

    /**
     * @test
     */
    public function shouldReturnCorrectMapOfConfigs(): void
    {
        //given
        $this->newConfigWithRuleAndMaxRules($this->countryCode, 2, $taxRule1 = TaxRule::linearRule(1, 6, 'tax1'));
        //and
        $this->newConfigWithRuleAndMaxRules($this->countryCode, 2, $taxRule2 = TaxRule::linearRule(2, 6, 'tax2'));
        //and
        $this->newConfigWithRuleAndMaxRules($this->countryCode2, 2, $taxRule3 = TaxRule::linearRule(1, 6, 'tax3'));

        //when
        $configMap = $this->taxConfigController->taxConfigs();

        //then
        self::assertSame(2, $configMap->length());
        self::assertSame(2, $configMap->get($this->countryCode)->get()->length());
        self::assertTrue($configMap->get($this->countryCode)->get()->contains($taxRule1));
        self::assertTrue($configMap->get($this->countryCode)->get()->contains($taxRule2));

        self::assertSame(1, $configMap->get($this->countryCode2)->get()->length());
        self::assertTrue($configMap->get($this->countryCode2)->get()->contains($taxRule3));
    }

    private function newConfigWithRuleAndMaxRules(string $countryCode, int $maxRules, TaxRule $aTaxRuleWithParams): TaxConfig
    {
        $taxConfig = new TaxConfig();
        $taxConfig->setCountryCode($countryCode);
        $taxConfig->setMaxRulesCount($maxRules);
        $taxConfig->setTaxRules(GenericList::of($aTaxRuleWithParams));
        return $this->taxConfigRepository->save($taxConfig);
    }
}
