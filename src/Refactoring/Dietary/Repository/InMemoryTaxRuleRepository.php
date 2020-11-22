<?php

namespace Refactoring\Dietary\Repository;

use Refactoring\Dietary\TaxRule;
use Refactoring\Dietary\TaxRuleRepository;

class InMemoryTaxRuleRepository implements TaxRuleRepository
{
    /**
     * @var array
     */
    private $taxRules = [];

    public function findByTaxCodeContaining(string $taxCode): TaxRule
    {
        // TODO: Implement findByTaxCodeContaining() method.
    }

    public function getOne(int $taxRuleId): TaxRule
    {
        // TODO: Implement getOne() method.
    }

    public function delete(TaxRule $taxRule): void
    {
        // TODO: Implement delete() method.
    }
}