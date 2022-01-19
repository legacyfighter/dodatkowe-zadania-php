<?php

namespace LegacyFighter\Dietary;

interface TaxConfigRepository
{
    /**
     * @param string $countryCode
     * @return TaxConfig
     */
    public function findByCountryCode(string $countryCode): ?TaxConfig;

    /**
     * @return array
     */
    public function findAll(): array;

    /**
     * @param TaxConfig $taxConfig
     * @return TaxConfig
     */
    public function save(TaxConfig $taxConfig): TaxConfig;

    /**
     * @param int $configId
     * @return TaxConfig
     */
    public function getOne(int $configId): TaxConfig;
}
