<?php declare(strict_types=1);

namespace Assignment\Calculator;

use Assignment\Database\Exception\DbConnectionException;
use Assignment\Product\ProductFactory;
use Assignment\Product\ProductFinderInterface;
use Assignment\Product\ProductQuantityReference;

final class CalculatorFactory
{

    /**
     * @param ProductQuantityReference[] $products
     * @param string $country
     * @return AbstractCalculator
     * @throws DbConnectionException
     */
    public function createCalculator(array $products, string $country): AbstractCalculator
    {
        return new Calculator(
            $this->createProductFinder(),
            $this->createCalculatedOutcomeFactory(),
            $this->createConsolidatedCalculationOutcomeFactory(),
            $products,
            $country
        );
    }

    /**
     * @return ProductFinderInterface
     * @throws DbConnectionException
     */
    private function createProductFinder(): ProductFinderInterface
    {
        return (new ProductFactory())->createProduct();
    }

    /**
     * @return ProductCalculationOutcomeFactory
     */
    private function createCalculatedOutcomeFactory(): ProductCalculationOutcomeFactory
    {
        return new ProductCalculationOutcomeFactory();
    }

    /**
     * @return ConsolidatedCalculationOutcomeFactory
     */
    private function createConsolidatedCalculationOutcomeFactory(): ConsolidatedCalculationOutcomeFactory
    {
        return new ConsolidatedCalculationOutcomeFactory();
    }
}
