<?php

namespace Refactoring\Dietary;

class TaxRule
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $taxCode;

    /**
     * @var bool
     */
    private $isLinear;

    /**
     * @var int
     */
    private $aFactor;

    /**
     * @var int
     */
    private $bFactor;

    /**
     * @var bool
     */
    private $isSquare;

    /**
     * @var int
     */
    private $aSquareFactor;

    /**
     * @var int
     */
    private $bSquareFactor;

    /**
     * @var int
     */
    private $cSquareFactor;

    /**
     * @var TaxConfig
     */
    private $taxConfig;

    /**
     * TaxRule constructor.
     */
    public function __construct()
    {
        $this->id = random_int(0, PHP_INT_MAX); // SHORTCUT
    }

    /**
     * @param int $a
     * @param int $b
     * @param string $taxCode
     */
    public static function linearRule(int $a, int $b, string $taxCode)
    {
        $rule = new TaxRule();

        $rule->setLinear(true);
        $rule->setTaxCode($taxCode);
        $rule->setaFactor($a);
        $rule->setbSquareFactor($b);
        $rule->setTaxCode($taxCode);

        return $rule;
    }

    /**
     * @return bool
     */
    public function isLinear(): bool
    {
        return $this->isLinear;
    }

    /**
     * @param bool $isLinear
     */
    public function setLinear(bool $isLinear): void
    {
        $this->isLinear = $isLinear;
    }

    /**
     * @return string
     */
    public function getTaxCode(): string
    {
        return $this->taxCode;
    }

    /**
     * @param string $taxCode
     */
    public function setTaxCode(string $taxCode): void
    {
        $this->taxCode = $taxCode;
    }

    /**
     * @return int
     */
    public function getaFactor(): int
    {
        return $this->aFactor;
    }

    /**
     * @param int $aFactor
     */
    public function setaFactor(int $aFactor): void
    {
        $this->aFactor = $aFactor;
    }

    /**
     * @return int
     */
    public function getbFactor(): int
    {
        return $this->bFactor;
    }

    /**
     * @param int $bFactor
     */
    public function setbFactor(int $bFactor): void
    {
        $this->bFactor = $bFactor;
    }

    /**
     * @return bool
     */
    public function isSquare(): bool
    {
        return $this->isSquare;
    }

    /**
     * @param bool $isSquare
     */
    public function setSquare(bool $isSquare): void
    {
        $this->isSquare = $isSquare;
    }

    /**
     * @return int
     */
    public function getaSquareFactor(): int
    {
        return $this->aSquareFactor;
    }

    /**
     * @param int $aSquareFactor
     */
    public function setaSquareFactor(int $aSquareFactor): void
    {
        $this->aSquareFactor = $aSquareFactor;
    }

    /**
     * @return int
     */
    public function getbSquareFactor(): int
    {
        return $this->bSquareFactor;
    }

    /**
     * @param int $bSquareFactor
     */
    public function setbSquareFactor(int $bSquareFactor): void
    {
        $this->bSquareFactor = $bSquareFactor;
    }

    /**
     * @return int
     */
    public function getcSquareFactor(): int
    {
        return $this->cSquareFactor;
    }

    /**
     * @param int $cSquareFactor
     */
    public function setcSquareFactor(int $cSquareFactor): void
    {
        $this->cSquareFactor = $cSquareFactor;
    }

    /**
     * @param $o
     * @return bool
     */
    public function equals($o): bool
    {
        // :)

        if ($this == $o) {
            return true;
        }

        if (!($o instanceof TaxRule)) {
            return false;
        }

        return $this->taxCode == $o->getTaxCode();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }
}