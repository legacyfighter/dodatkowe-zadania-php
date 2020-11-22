<?php

namespace Refactoring\Dietary\Repository;

use phpstream\collectors\ArrayCollector;
use phpstream\impl\MemoryStream;
use Refactoring\Dietary\TaxConfig;
use Refactoring\Dietary\TaxConfigRepository;

class InMemoryTaxConfigRepository implements TaxConfigRepository
{
    /**
     * @var TagConfig[]
     */
    private $taxConfigs = [];

    /**
     * @param string $countryCode
     * @return TaxConfig|null
     */
    public function findByCountryCode(string $countryCode): ?TaxConfig
    {
        return (new MemoryStream($this->taxConfigs))
            ->filter(function (TaxConfig $config) use ($countryCode) {
                return $config->getCountryCode() == $countryCode;
            })
            ->collect(new ArrayCollector());
    }

    /**
     * @return array
     */
    public function findAll(): array
    {
        return array_values($this->taxConfigs);
    }

    /**
     * @param TaxConfig $taxConfig
     * @return TaxConfig
     */
    public function save(TaxConfig $taxConfig): TaxConfig
    {
        $this->taxConfigs[$taxConfig->getId()] = $taxConfig;
    }

    /**
     * @param int $configId
     * @return TaxConfig
     */
    public function getOne(int $configId): TaxConfig
    {
        return $this->taxConfigs[$configId];
    }
}