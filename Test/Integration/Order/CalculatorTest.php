<?php

namespace Assignment\Test\Integration\Order;

use Assignment\Calculator\CalculatorFactory;
use Assignment\Database\Exception\DbConnectionException;
use Assignment\Order\Resource\OrderResource;
use Assignment\Product\Exception\CannotFindProductException;
use Assignment\Product\ProductQuantityReference;
use PHPUnit\Framework\TestCase;

class CalculatorTest extends TestCase
{
    const PRODUCT_MILK = 'product-milk';
    const PRODUCT_BREAD = 'product-bread';
    const COUNTRY_FI = 'FI';

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        // In reality, we will prepare test data here.
        // For now, we shall use the same data from the migration here.
    }


    /**
     * @throws DbConnectionException
     * @throws CannotFindProductException
     */
    public function testCalculate(): void
    {
        $expectedGrandTotal = 34.34;
        $expectedTotalTaxPrice = 4.64;

        $productQuantityReferences = [
            $this->createProductQuantityReference(self::PRODUCT_MILK, 10),
            $this->createProductQuantityReference(self::PRODUCT_BREAD, 5),
        ];

        $calculatedOutcome = (new CalculatorFactory())->createCalculator($productQuantityReferences, self::COUNTRY_FI)->calculate();
        self::assertInstanceOf(OrderResource::class, $calculatedOutcome);
        self::assertEquals($expectedGrandTotal, $calculatedOutcome->getGrandTotal());
        self::assertEquals($expectedTotalTaxPrice, $calculatedOutcome->getGrandTotalTaxPrice());
    }

    /**
     * @param string $productId
     * @param int $quantity
     * @return ProductQuantityReference
     */
    private function createProductQuantityReference(string $productId, int $quantity): ProductQuantityReference
    {
        return new ProductQuantityReference($productId, $quantity);
    }
}
