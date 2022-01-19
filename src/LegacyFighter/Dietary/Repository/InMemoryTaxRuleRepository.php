<?php

namespace LegacyFighter\Dietary\Repository;

use Munus\Collection\GenericList;
use Munus\Collection\Stream\Collector\GenericCollector;
use Munus\Control\Either;
use phpstream\collectors\ArrayCollector;
use phpstream\impl\MemoryStream;
use LegacyFighter\Dietary\TaxConfig;
use LegacyFighter\Dietary\TaxConfigRepository;
use LegacyFighter\Dietary\TaxRule;
use LegacyFighter\Dietary\TaxRuleRepository;

class InMemoryTaxRuleRepository implements TaxRuleRepository
{
    /**
     * @var TaxConfigRepository
     */
    private $taxConfigRepository;

    /**
     * InMemoryTaxRuleRepository constructor.
     * @param TaxConfigRepository $taxConfigRepository
     */
    public function __construct(TaxConfigRepository $taxConfigRepository)
    {
        $this->taxConfigRepository = $taxConfigRepository;
    }

    public function findByTaxCodeContaining(string $taxCode): TaxRule
    {
        // TODO: Implement findByTaxCodeContaining() method.
    }

    /**
     * @param int $taxRuleId
     * @return TaxRule
     */
    public function getOne(int $taxRuleId): TaxRule
    {
        /**
         * @var $taxConfig TaxConfig
         */
        foreach ($this->taxConfigRepository->findAll() as $taxConfig) {
            $rules = $taxConfig->getTaxRules();
            $filtered = $rules->filter(function (TaxRule $taxRule) use ($taxRuleId) {
                return $taxRule->getId() == $taxRuleId;
            });

            if ($filtered->length() > 0) {
                return $filtered->head();
            }
        }

        // Exception flow
    }

    public function delete(TaxRule $taxRule): void
    {
        // TODO: Implement delete() method.
    }
}
