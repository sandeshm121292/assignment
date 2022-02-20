<?php

namespace Assignment\Test\Integration\Order;

use Assignment\Database\Exception\DbConnectionException;
use Assignment\Order\Calculator\CalculatorFactory;
use Assignment\Order\Controller\Form\ProductQuantityReference;
use Assignment\Product\Exception\CannotFindProductException;
use PHPUnit\Framework\TestCase;

class CalculatorTest extends TestCase
{

    /**
     * @throws DbConnectionException
     * @throws CannotFindProductException
     */
    public function testCalculate(): void
    {

        $productQuantityReferences = [
            $this->createProductQuantityReference('product-milk', 10),
            $this->createProductQuantityReference('product-bread', 5),
        ];


        $calculatedOutcome = (new CalculatorFactory())->createCalculator($productQuantityReferences, 'FI')->calculate();
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
