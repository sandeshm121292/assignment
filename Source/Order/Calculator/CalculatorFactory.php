<?php declare(strict_types=1);

namespace Assignment\Order\Calculator;

use Assignment\Database\Exception\DbConnectionException;
use Assignment\Order\Resource\OrderResourceFactory;
use Assignment\Product\ProductFactory;
use Assignment\Product\ProductFinderInterface;

final class CalculatorFactory
{

    /**
     * @throws DbConnectionException
     */
    public function createCalculator($products, $country): Calculator
    {
        return new Calculator($this->createProductFinder(), $this->createCalculatedOutcomeFactory(), $this->createInvoiceResourceFactory(), $products, $country);
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

    /**
     * @return OrderResourceFactory
     */
    private function createInvoiceResourceFactory(): OrderResourceFactory
    {
        return new OrderResourceFactory();
    }
}