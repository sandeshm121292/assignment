<?php declare(strict_types=1);

namespace Assignment\Calculator;

final class CalculatedOutcomeFactory
{
    /**
     * @param string $productId
     * @param int $quantity
     * @param float $basePrice
     * @param float $taxPrice
     * @param float $totalPrice
     *
     * @return CalculatedOutcome
     */
    public function createCalculatedOutcome(string $productId, int $quantity, float $basePrice, float $taxPrice, float $totalPrice): CalculatedOutcome
    {
        return new CalculatedOutcome($productId, $quantity, $basePrice, $taxPrice, $totalPrice);
    }
}
