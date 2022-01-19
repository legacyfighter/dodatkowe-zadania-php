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
    public function __construct()
    {
        $this->id = random_int(0, PHP_INT_MAX); // SHORTCUT
        $this->taxRules = GenericList::empty();
    }


    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getCountryReason(): string
    {
        return $this->countryReason;
    }

    /**
     * @param string $countryReason
     */
    public function setCountryReason(string $countryReason): void
    {
        $this->countryReason = $countryReason;
    }

    /**
     * @return string
     */
    public function getCountryCode(): string
    {
        return $this->countryCode;
    }

    /**
     * @param string $countryCode
     */
    public function setCountryCode(string $countryCode): void
    {
        $this->countryCode = $countryCode;
    }

    /**
     * @return \DateTime
     */
    public function getLastModifiedDate(): \DateTime
    {
        return $this->lastModifiedDate;
    }

    /**
     * @param \DateTime $lastModifiedDate
     */
    public function setLastModifiedDate(\DateTime $lastModifiedDate): void
    {
        $this->lastModifiedDate = $lastModifiedDate;
    }

    /**
     * @return int
     */
    public function getCurrentRulesCount(): int
    {
        return $this->currentRulesCount;
    }

    /**
     * @param int $currentRulesCount
     */
    public function setCurrentRulesCount(int $currentRulesCount): void
    {
        $this->currentRulesCount = $currentRulesCount;
    }

    /**
     * @return int
     */
    public function getMaxRulesCount(): int
    {
        return $this->maxRulesCount;
    }

    /**
     * @param int $maxRulesCount
     */
    public function setMaxRulesCount(int $maxRulesCount): void
    {
        $this->maxRulesCount = $maxRulesCount;
    }

    /**
     * @return GenericList
     */
    public function getTaxRules(): GenericList
    {
        return $this->taxRules;
    }

    /**
     * @param GenericList $taxRules
     */
    public function setTaxRules(GenericList $taxRules): void
    {
        $this->taxRules = $taxRules;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }
}
