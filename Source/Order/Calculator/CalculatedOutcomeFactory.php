<?php declare(strict_types=1);

namespace Assignment\Order\Calculator;

final class CalculatedOutcomeFactory
{
    /**
     * @param string $productId
     * @param int $quantity
     * @param float $unitCost
     * @param float $taxedCost
     * @param float $totalCost
     *
     * @return CalculatedOutcome
     */
    public function createCalculatedOutcome(string $productId, int $quantity, float $unitCost, float $taxedCost, float $totalCost): CalculatedOutcome
    {
        return new CalculatedOutcome($productId, $quantity, $unitCost, $taxedCost, $totalCost);
    }
}
