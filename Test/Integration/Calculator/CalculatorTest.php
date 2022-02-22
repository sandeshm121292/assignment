<?php declare(strict_types=1);

namespace Assignment\Test\Integration\Calculator;

use Assignment\Calculator\CalculatorFactory;
use Assignment\Calculator\ConsolidatedCalculationOutcome;
use Assignment\Calculator\ProductCalculationOutcome;
use Assignment\Database\Exception\DbConnectionException;
use Assignment\Product\Exception\CannotFindProductException;
use Assignment\Product\ProductQuantityReference;
use Assignment\Test\Integration\BaseIntegrationTest;
use Exception;

/**
 * @see \Assignment\Calculator\Calculator
 */
final class CalculatorTest extends BaseIntegrationTest
{
    const PRODUCT_MILK = 'product-milk-2';

    const MILK_PRICE = 2.5;
    const MILK_COUNTRY_PL = 'PL';
    const MILK_TAX_PL = 2.5;

    const MILK_COUNTRY_FI = 'FI';
    const MILK_TAX_FI = 12.5;

    const PRODUCT_BREAD = 'product-bread-2';
    const BREAD_PRICE = 3;
    const BREAD_COUNTRY = 'FI';
    const BREAD_TAX = 12.5;

    const CAL_BASE_PRICE = 'basePrice';
    const CAL_TAX_PRICE = 'taxPrice';
    const CAL_TOTAL_PRICE = 'totalPrice';

    /**
     * @return void
     * @throws DbConnectionException
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->clearProducts(self::PRODUCT_MILK, self::MILK_COUNTRY_PL);
        $this->clearProducts(self::PRODUCT_MILK, self::MILK_COUNTRY_FI);
        $this->clearProducts(self::PRODUCT_BREAD, self::MILK_COUNTRY_FI);

        $this->storeProduct(self::PRODUCT_MILK, self::MILK_PRICE, self::MILK_COUNTRY_PL, self::MILK_TAX_PL);
        $this->storeProduct(self::PRODUCT_MILK, self::MILK_PRICE, self::MILK_COUNTRY_FI, self::MILK_TAX_FI);
        $this->storeProduct(self::PRODUCT_BREAD, self::BREAD_PRICE, self::BREAD_COUNTRY, self::BREAD_TAX);
    }

    /**
     * @return void
     * @throws DbConnectionException
     */
    protected function tearDown(): void
    {
        $this->clearProducts(self::PRODUCT_MILK, self::MILK_COUNTRY_PL);
        $this->clearProducts(self::PRODUCT_MILK, self::MILK_COUNTRY_FI);
        $this->clearProducts(self::PRODUCT_BREAD, self::MILK_COUNTRY_FI);
        parent::tearDown();
    }

    /**
     * @throws DbConnectionException
     * @throws CannotFindProductException
     */
    public function testCalculateSuccess(): void
    {
        // hardcoding the expectations
        $productBreadQuantity = 5;
        $calculatedResults = $this->calculate(self::BREAD_PRICE, self::BREAD_TAX, $productBreadQuantity);
        $productBreadExpectedBasePrice = $calculatedResults[self::CAL_BASE_PRICE];
        $productBreadExpectedTaxPrice = $calculatedResults[self::CAL_TAX_PRICE];
        $productBreadExpectedTotalPrice = $calculatedResults[self::CAL_TOTAL_PRICE];

        $productMilkQuantity = 10;
        $calculatedResults = $this->calculate(self::MILK_PRICE, self::MILK_TAX_FI, $productMilkQuantity);
        $productMilkExpectedBasePrice = $calculatedResults[self::CAL_BASE_PRICE];
        $productMilkExpectedTaxPrice = $calculatedResults[self::CAL_TAX_PRICE];
        $productMilkExpectedTotalPrice = $calculatedResults[self::CAL_TOTAL_PRICE];

        $expectedGrandTotal = $productBreadExpectedTotalPrice + $productMilkExpectedTotalPrice;
        $expectedTotalTaxPrice = $productBreadExpectedTaxPrice + $productMilkExpectedTaxPrice;

        $productQuantityReferences = [
            $this->createProductQuantityReference(self::PRODUCT_MILK, $productMilkQuantity),
            $this->createProductQuantityReference(self::PRODUCT_BREAD, $productBreadQuantity),
        ];

        $outcome = (new CalculatorFactory())->createCalculator($productQuantityReferences, 'FI')->calculate();

        self::assertInstanceOf(ConsolidatedCalculationOutcome::class, $outcome);
        self::assertEquals($expectedGrandTotal, $outcome->getGrandTotalPrice());
        self::assertEquals($expectedTotalTaxPrice, $outcome->getGrandTotalTaxPrice());

        $productCalculationOutcomes = $outcome->getProductCalculationOutcomes();
        self::assertCount(2, $productCalculationOutcomes);
        $productBread = $this->getProductIdFromArray(self::PRODUCT_BREAD, $productCalculationOutcomes);
        self::assertNotNull($productBread);
        self::assertSame($productBreadQuantity, $productBread->getQuantity());
        self::assertSame($productBreadExpectedBasePrice, $productBread->getBasePrice());
        self::assertSame($productBreadExpectedTaxPrice, $productBread->getTaxPrice());
        self::assertSame($productBreadExpectedTotalPrice, $productBread->getTotalPrice());

        $productMilk = $this->getProductIdFromArray(self::PRODUCT_MILK, $productCalculationOutcomes);
        self::assertNotNull($productMilk);
        self::assertSame($productMilkQuantity, $productMilk->getQuantity());
        self::assertSame($productMilkExpectedBasePrice, $productMilk->getBasePrice());
        self::assertSame($productMilkExpectedTaxPrice, $productMilk->getTaxPrice());
        self::assertSame($productMilkExpectedTotalPrice, $productMilk->getTotalPrice());
    }

    /**
     * @return void
     */
    public function testCalculateErrorOnMissingOrInvalidProductId(): void
    {
        $invalidProductId = 'Invalid-product';
        $productQuantityReferences = [
            $this->createProductQuantityReference($invalidProductId, 10),
            $this->createProductQuantityReference(self::PRODUCT_BREAD, 5),
        ];

        try {
            (new CalculatorFactory())->createCalculator($productQuantityReferences, 'FI')->calculate();
            self::fail(sprintf("%s was not thrown", CannotFindProductException::class));
        } catch (Exception $exception) {
            self::assertInstanceOf(CannotFindProductException::class, $exception);
            self::assertStringContainsString($invalidProductId, $exception->getMessage());
        }
    }

    /**
     * @param mixed $quantity
     * @return void
     * @dataProvider provideInvalidQuantity
     */
    public function testCalculateErrorOnInvalidQuantity($quantity): void
    {
        $productQuantityReferences = [
            $this->createProductQuantityReference(self::PRODUCT_BREAD, $quantity),
        ];

        try {
            (new CalculatorFactory())->createCalculator($productQuantityReferences, 'FI')->calculate();
            self::fail(sprintf("%s was not thrown", CannotFindProductException::class));
        } catch (Exception $exception) {
            self::assertInstanceOf(CannotFindProductException::class, $exception);
            self::assertStringContainsString((string) $quantity, $exception->getMessage());
        }
    }

    /**
     * @return array
     */
    public function provideInvalidQuantity(): array
    {
        return [
            [-1],
            [0],
        ];
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

    /**
     * @param string $id
     * @param ProductCalculationOutcome[] $productCalculationOutcomes
     * @return ProductCalculationOutcome
     */
    private function getProductIdFromArray(string $id, array $productCalculationOutcomes): ?ProductCalculationOutcome
    {
        foreach ($productCalculationOutcomes as $val) {
            if ($val->getProductId() === $id) {
                return $val;
            }
        }
        return null;
    }


    /**
     * @param float $productPrice
     * @param float $tax
     * @param float $quantity
     * @return float[]
     */
    private function calculate(float $productPrice, float $tax, float $quantity): array
    {
        $price = round($productPrice * $quantity, 2);
        $taxPrice = round(($price / 100) * $tax, 2);

        return [
            self::CAL_BASE_PRICE => $price,
            self::CAL_TAX_PRICE => $taxPrice,
            self::CAL_TOTAL_PRICE => $price + $taxPrice
        ];
    }
}
