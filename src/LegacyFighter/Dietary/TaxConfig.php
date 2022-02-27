<?php

namespace LegacyFighter\Dietary;

use Munus\Collection\GenericList;

class TaxConfig
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $description;

    /**
     * @var string
     */
    private $countryReason;

    /**
     * @var string
     */
    private $countryCode;

    /**
     * @var \DateTime
     */
    private $lastModifiedDate;

    /**
     * @var int
     */
    private $currentRulesCount;

    /**
     * @var int
     */
    private $maxRulesCount;

    /**
     * @var GenericList
     */
    private $taxRules;

    /**
     * TaxConfig constructor.
     */
    public function __construct(int $maxRulesCount, String $countryCode)
    {
        $this->id = random_int(0, PHP_INT_MAX); // SHORTCUT
        $this->taxRules = GenericList::empty();
        $this->maxRulesCount = $maxRulesCount;
        $this->countryCode = $countryCode;
    }

    public function add(TaxRule $taxRule): void
    {
        if ($this->maxRulesCount <= $this->taxRules->length()) {
            throw new \Exception("Too many rules");
        }

        $this->taxRules = $this->taxRules->append($taxRule);
        $this->currentRulesCount++;
        $this->lastModifiedDate = new \DateTime();
    }

    public function remove(TaxRule $taxRule): void
    {
        $taxRuleId = $taxRule->getId();

        if ($this->taxRules->contains($taxRule)) {
            if ($this->taxRules->length() === 1) {
                throw new \Exception("Last rule in country config");
            }

            $this->taxRules = $this->taxRules->filter(function (TaxRule $taxRule) use ($taxRuleId) {
                return $taxRule->getId() !== $taxRuleId;
            });
            $this->currentRulesCount--;
            $this->lastModifiedDate = new \DateTime();
        }
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return string
     */
    public function getCountryReason(): string
    {
        return $this->countryReason;
    }

    /**
     * @return string
     */
    public function getCountryCode(): string
    {
        return $this->countryCode;
    }

    /**
     * @return \DateTime
     */
    public function getLastModifiedDate(): \DateTime
    {
        return $this->lastModifiedDate;
    }

    /**
     * @return int
     */
    public function getCurrentRulesCount(): int
    {
        return $this->currentRulesCount;
    }

    /**
     * @return int
     */
    public function getMaxRulesCount(): int
    {
        return $this->maxRulesCount;
    }

    /**
     * @return GenericList
     */
    public function getTaxRules(): GenericList
    {
        return $this->taxRules; // Zwrócenie niemutowalnej kolekcji
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }
}
