<?php

namespace LegacyFighter\Dietary;

interface TaxRuleRepository
{
    /**
     * @param string $taxCode
     * @return TaxRule
     */
    public function findByTaxCodeContaining(string $taxCode): TaxRule;

    /**
     * @param int $taxRuleId
     * @return TaxRule
     */
    public function getOne(int $taxRuleId): TaxRule;

    /**
     * @param TaxRule $taxRule
     */
    public function delete(TaxRule $taxRule): void;
}
