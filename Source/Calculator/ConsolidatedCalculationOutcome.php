<?php declare(strict_types=1);

namespace Assignment\Calculator;

class ConsolidatedCalculationOutcome
{

    /**
     * @var float
     */
    private $preCalculationTotal;

    /**
     * @var float
     */
    private $postCalculationTotal;

    /**
     * @var ProductCalculationOutcome[]
     */
    private $productCalculationOutcomes = [];

    /**
     * @param ProductCalculationOutcome $productCalculationOutcome
     * @return void
     */
    public function addProductCalculationOutcome(ProductCalculationOutcome $productCalculationOutcome): void
    {
        $this->productCalculationOutcomes[] = $productCalculationOutcome;
    }

    /**
     * @param float $preCalculationTotal
     */
    public function setPreCalculationPrice(float $preCalculationTotal): void
    {
        $this->preCalculationTotal = $preCalculationTotal;
    }

    /**
     * @param float $postCalculationTotal
     */
    public function setPostCalculationPrice(float $postCalculationTotal): void
    {
        $this->postCalculationTotal = $postCalculationTotal;
    }

    /**
     * @return ProductCalculationOutcome[]
     */
    public function getProductCalculationOutcomes(): array
    {
        return $this->productCalculationOutcomes;
    }

    /**
     * @return float
     */
    public function getGrandTotalPrice(): float
    {
        $total = 0;

        foreach ($this->productCalculationOutcomes as $outcome) {
            $total += $outcome->getTotalPrice();
        }

        $total = round($total, 2);

        return $this->preCalculationTotal + $total + $this->postCalculationTotal;
    }

    /**
     * @return float
     */
    public function getGrandTotalTaxPrice(): float
    {
        $total = 0;

        foreach ($this->productCalculationOutcomes as $outcome) {
            $total += $outcome->getTaxPrice();
        }

        return round($total, 2);
    }
}
