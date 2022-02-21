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
     * @return Calculator
     * @throws DbConnectionException
     */
    public function createCalculator(array $products, string $country): Calculator
    {
        return new Calculator($this->createProductFinder(), $this->createCalculatedOutcomeFactory(), $products, $country);
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
     * @return CalculatedOutcomeFactory
     */
    private function createCalculatedOutcomeFactory(): CalculatedOutcomeFactory
    {
        return new CalculatedOutcomeFactory();
    }
}
