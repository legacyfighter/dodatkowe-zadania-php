<?php

namespace LegacyFighter\Dietary;

use Munus\Collection\GenericList;
use Munus\Collection\Map;

class TaxConfigController
{
    /**
     * @var TaxRuleService
     */
    private $taxRuleService;

    /**
     * TaxConfigController constructor.
     * @param TaxRuleService $taxRuleService
     */
    public function __construct(TaxRuleService $taxRuleService)
    {
        $this->taxRuleService = $taxRuleService;
    }

    public function taxConfigs(): Map
    {
        $taxConfigs = $this->taxRuleService->findAllConfigs();
        $map = Map::empty();

        foreach ($taxConfigs as $taxConfig) {
            /**
             * @var $taxConfig TaxConfig
             */
            if (!$map->containsKey($taxConfig->getCountryCode())) {
                if ($taxConfig->getTaxRules()->isEmpty()) {
                    $map = $map->put($taxConfig->getCountryCode(), GenericList::empty());
                } else {
                    $map = $map->put($taxConfig->getCountryCode(), $taxConfig->getTaxRules());
                }
            } else {
                $map = $map->put(
                    $taxConfig->getCountryCode(),
                    $map->get($taxConfig->getCountryCode())->get()->appendAll($taxConfig->getTaxRules())
                );
            }
        }

        $newRuleMap = Map::empty();
        foreach ($map->toArray() as $key => $list) {
            $existed = [];
            $newList = $list->filter(function(TaxRule $taxRule) use ($existed):bool {
                if(!isset($existed[$taxRule->getTaxCode()])) {
                    $existed[$taxRule->getTaxCode()] = true;
                    return true;
                }
                return false;
            });
            $newRuleMap = $newRuleMap->put($key, $newList);
        }

        return $newRuleMap;
    }
}
