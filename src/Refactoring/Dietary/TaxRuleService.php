<?php

namespace Refactoring\Dietary;

use Munus\Collection\GenericList;

class TaxRuleService
{
    /**
     * @var TaxRuleRepository
     */
    private $taxRuleRepository;

    /**
     * @var TaxConfigRepository
     */
    private $taxConfigRepository;

    /**
     * @var OrderRepository
     */
    private $orderRepository;

    /**
     * TaxRuleService constructor.
     * @param TaxRuleRepository $taxRuleRepository
     * @param TaxConfigRepository $taxConfigRepository
     * @param OrderRepository $orderRepository
     */
    public function __construct(TaxRuleRepository $taxRuleRepository, TaxConfigRepository $taxConfigRepository, OrderRepository $orderRepository)
    {
        $this->taxRuleRepository = $taxRuleRepository;
        $this->taxConfigRepository = $taxConfigRepository;
        $this->orderRepository = $orderRepository;
    }

    public function addTaxRuleToCountry(string $countryCode, int $aFactor, int $bFactor, string $taxCode)
    {
        if ($countryCode === null || $countryCode == "" || strlen($countryCode) == 1) {
            throw new \Exception("Invalid country code");
        }
        if ($aFactor == 0) {
            throw new \Exception("Invalid aFactor");
        }

        $taxRule = new TaxRule();

        $taxRule->setaFactor($aFactor);
        $taxRule->setbFactor($bFactor);
        $taxRule->setLinear(true);
        $year = (int)date('Y');
        $taxRule->setTaxCode("A. 899. " . $year . $taxCode);
        $taxConfig = $this->taxConfigRepository->findByCountryCode($countryCode);

        if ($taxConfig == null) {
            $taxConfig = $this->createTaxConfigWithRule($countryCode, $taxRule);

            return;
        }

        $taxConfig->add($taxRule);

        $this->taxConfigRepository->save($taxConfig);
    }

    /**
     * @param string $countryCode
     * @param TaxRule $taxRule
     * @return TaxConfig
     * @throws \Exception
     */
    public function createTaxConfigWithRule(string $countryCode, TaxRule $taxRule): TaxConfig
    {
        if ($countryCode == null || $countryCode == "" || strlen($countryCode) == 1) {
            throw new \Exception("Invalid country code");
        }

        $taxConfig = new TaxConfig(10, $countryCode);

        $taxConfig->add($taxRule);

        $this->taxConfigRepository->save($taxConfig);

        return $taxConfig;
    }

    public function createTaxConfigWithRuleAndMaxCount(string $countryCode, int $maxRulesCount, TaxRule $taxRule): TaxConfig
    {
        if ($countryCode == null || $countryCode == "" || strlen($countryCode) == 1) {
            throw new \Exception("Invalid country code");
        }

        $taxConfig = new TaxConfig($maxRulesCount, $countryCode);

        $taxConfig->add($taxRule);

        $this->taxConfigRepository->save($taxConfig);

        return $taxConfig;
    }

    public function addTaxRuleToCountry2(string $countryCode, int $aFactor, int $bFactor, int $cFactor, string $taxCode): void
    {
        if ($aFactor == 0) {
            throw new \Exception("Invalid aFactor");
        }

        if ($countryCode == null || $countryCode == "" || strlen($countryCode) == 1) {
            throw new \Exception("Invalid country code");
        }

        $taxRule = new TaxRule();
        $taxRule->setaSquareFactor($aFactor);
        $taxRule->setbSquareFactor($bFactor);
        $taxRule->setcSquareFactor($cFactor);
        $taxRule->setSquare(true);
        $year = (int)date('Y');
        $taxRule->setTaxCode("A. 899. " . $year . $taxCode);

        $taxConfig = $this->taxConfigRepository->findByCountryCode($countryCode);

        if ($taxConfig == null) {
            $taxConfig = $this->createTaxConfigWithRule($countryCode, $taxRule);
        }

        $taxConfig->add($taxRule);

        $this->taxConfigRepository->save($taxConfig);
    }

    /**
     * @param int $taxRuleId
     * @param int $configId
     * @throws \Exception
     */
    public function deleteRule(int $taxRuleId, int $configId) {
        $taxRule = $this->taxRuleRepository->getOne($taxRuleId);
        $taxConfig = $this->taxConfigRepository->getOne($configId);

        $taxConfig->remove($taxRule);

        $this->taxRuleRepository->delete($taxRule);
        $this->taxConfigRepository->save($taxConfig);
    }

    /**
     * @param string $countryCode
     * @return GenericList
     */
    public function findRules(string $countryCode): GenericList
    {
        return $this->taxConfigRepository->findByCountryCode($countryCode)->getTaxRules();
    }

    /**
     * @param string $countryCode
     * @return int
     */
    public function rulesCount(string $countryCode): int
    {
        return $this->taxConfigRepository->findByCountryCode($countryCode)->getCurrentRulesCount();
    }

    /**
     * @return array
     */
    public function findAllConfigs(): array
    {
        return $this->taxConfigRepository->findAll();
    }
}