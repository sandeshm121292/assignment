<?php declare(strict_types=1);

namespace Assignment\Calculator;

final class ProductCalculationOutcomeFactory
{
    /**
     * @param string $productId
     * @param int $quantity
     * @param float $basePrice
     * @param float $taxPrice
     * @param float $totalPrice
     *
     * @return ProductCalculationOutcome
     */
    public function createCalculatedOutcome(string $productId, int $quantity, float $basePrice, float $taxPrice, float $totalPrice): ProductCalculationOutcome
    {
        return new ProductCalculationOutcome($productId, $quantity, $basePrice, $taxPrice, $totalPrice);
    }
}
