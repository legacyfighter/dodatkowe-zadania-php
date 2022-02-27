<?php

namespace LegacyFighter\Dietary\Repository;

use LegacyFighter\Dietary\TaxConfig;
use LegacyFighter\Dietary\TaxConfigRepository;

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
        /**
         * @var $taxConfig TaxConfig
         */
        foreach ($this->taxConfigs as $taxConfig) {
            if ($taxConfig->getCountryCode() == $countryCode) {
                return $taxConfig;
            }
        }

        return null;
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

        return $taxConfig;
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
