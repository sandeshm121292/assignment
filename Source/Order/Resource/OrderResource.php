<?php declare(strict_types=1);

namespace Assignment\Order\Resource;

use Assignment\Order\Calculator\CalculatedOutcome;

class OrderResource
{

    /**
     * @var float
     */
    private $totalCost;

    /**
     * @var float
     */
    private $totalTaxCost;

    /**
     * @var CalculatedOutcome[]
     */
    private $calculatedOutComes;

    /**
     * @param float $totalCost
     * @param float $totalTaxCost
     * @param CalculatedOutcome[] $calculatedOutcomes
     */
    public function __construct(float $totalCost, float $totalTaxCost, array $calculatedOutcomes)
    {
        $this->totalCost = $totalCost;
        $this->totalTaxCost = $totalTaxCost;
        $this->calculatedOutComes = $calculatedOutcomes;
    }

    /**
     * @return float
     */
    public function getTotalCost(): float
    {
        return $this->totalCost;
    }

    /**
     * @return float
     */
    public function getTotalTaxCost(): float
    {
        return $this->totalTaxCost;
    }

    /**
     * @return CalculatedOutcome[]
     */
    public function getCalculatedOutComes(): array
    {
        return $this->calculatedOutComes;
    }
}