<?php

namespace Refactoring\Dietary\NewProducts;

use Munus\Collection\GenericList;
use Refactoring\Dietary\Order;
use Refactoring\Dietary\OrderRepository;
use Refactoring\Dietary\OrderState;
use Refactoring\Dietary\TaxConfig;
use Refactoring\Dietary\TaxConfigRepository;
use Refactoring\Dietary\TaxRule;
use Refactoring\Dietary\TaxRuleRepository;

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

        if ($taxConfig->getMaxRulesCount() <= $taxConfig->getTaxRules()->length()) {
            throw new \Exception("Too many rules");
        }

        $taxConfig->getTaxRules()->append($taxRule);
        $taxConfig->setCurrentRulesCount($taxConfig->getCurrentRulesCount() + 1);
        $taxConfig->setLastModifiedDate(new \DateTime());

        $this->taxConfigRepository->save($taxConfig);

//        $byOrderState = $this->orderRepository->findByOrderState(OrderState::INITIAL());
//
//        foreach ($byOrderState as $order) {
//            if (ordergetCustomerOrderGroup().getCustomer().getType().equals(Customer.Type.Person)) {
//                order.getTaxRules().add(taxRule);
//                orderRepository.save(order);
//            }
//        }
    }

    /**
     * @param string $countryCode
     * @param TaxRule $taxRule
     * @return TaxConfig
     * @throws \Exception
     */
    public function createTaxConfigWithRule(string $countryCode, TaxRule $taxRule): TaxConfig
    {
        $taxConfig = new TaxConfig();

        $taxConfig->setCountryCode($countryCode);
        $taxConfig->getTaxRules()->append($taxRule);
        $taxConfig->setCurrentRulesCount($taxConfig->getTaxRules()->length());
        $taxConfig->setMaxRulesCount(10);
        $taxConfig->setLastModifiedDate(new \DateTime());

        if ($countryCode == null || $countryCode == "" || strlen($countryCode) == 1) {
            throw new \Exception("Invalid country code");
        }

        $this->taxConfigRepository->save($taxConfig);

        return $taxConfig;
    }

    public function createTaxConfigWithRuleAndMaxCount(string $countryCode, int $maxRulesCount, TaxRule $taxRule): TaxConfig
    {
        $taxConfig = new TaxConfig();

        $taxConfig->setCountryCode($countryCode);
        $taxConfig->getTaxRules()->append($taxRule);
        $taxConfig->setCurrentRulesCount($taxConfig->getTaxRules()->length());
        $taxConfig->setMaxRulesCount($maxRulesCount);
        $taxConfig->setLastModifiedDate(new \DateTime());

        if ($countryCode == null || $countryCode == "" || strlen($countryCode) == 1) {
            throw new \Exception("Invalid country code");
        }

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
        $taxRule->setcSuqreFactor($cFactor);
        $taxRule->setSquare(true);
        $year = (int)date('Y');
        $taxRule->setTaxCode("A. 899. " . $year . $taxCode);

        $taxConfig = $this->taxConfigRepository->findByCountryCode($countryCode);

        if ($taxConfig == null) {
            $this->createTaxConfigWithRule($countryCode, $taxRule);
        }

        $taxConfig->getTaxRules()->append($taxRule);
        $taxConfig->setCurrentRulesCount($taxConfig->getCurrentRulesCount() + 1);
        $taxConfig->setLastModifiedDate(new \DateTime());

        $this->taxConfigRepository->save($taxConfig);
    }

    public function deleteRule(int $taxRuleId, int $configId) {
        $taxRule = $this->taxRuleRepository->getOne($taxRuleId);
        $taxConfig = $this->taxConfigRepository->getOne($configId);

        if ($taxConfig->getTaxRules()->contains($taxRule)) {
            if ($taxConfig->getTaxRules()->length() == 1) {
                throw new \Exception("Last rule in country config");
            }

            $this->taxRuleRepository->delete($taxRule);
            //$taxConfig->getTaxRules()->remove(taxRule);   IMPLEMENT!
            $taxConfig->setLastModifiedDate(new \DateTime());
        }

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