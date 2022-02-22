<?php

namespace Assignment\Test\Integration\Product;

use Assignment\Database\Exception\DbConnectionException;
use Assignment\Product\Exception\CannotFindProductException;
use Assignment\Product\Product;
use Assignment\Product\ProductFactory;
use Assignment\Product\ProductInterface;
use Assignment\Test\Integration\BaseIntegrationTest;

final class ProductTest extends BaseIntegrationTest
{

    /**
     * @return void
     * @throws DbConnectionException
     * @throws CannotFindProductException
     */
    public function testFindProduct(): void
    {
        $expectedProductId = 'product-bread-2';
        $expectedCountryCode = 'FI';
        $this->clearProducts($expectedProductId, $expectedCountryCode);

        $expectedTax = 12.5;
        $expectedPrice = 1.55;
        $expectedTitle = 'test-title';

        $this->storeProduct($expectedProductId, $expectedPrice, $expectedCountryCode, $expectedTax, $expectedTitle);
        $productResult = $this->createProduct()->findProduct($expectedProductId, 'FI');
        self::assertInstanceOf(ProductInterface::class, $productResult);
        self::assertSame($productResult->getTitle(), $expectedTitle);
        self::assertSame($productResult->getCountryCode(), $expectedCountryCode);
        self::assertSame($productResult->getTax(), $expectedTax);
        self::assertSame($productResult->getPrice(), $expectedPrice);
    }

    /**
     * @return void
     * @throws CannotFindProductException
     * @throws DbConnectionException
     */
    public function testFindReturnsNull(): void {
        $productResult = $this->createProduct()->findProduct('unknown-product', 'FI');
        self::assertNull($productResult);
    }

    /**
     * @return Product
     * @throws DbConnectionException
     */
    private function createProduct(): Product
    {
        return (new ProductFactory())->createProduct();
    }
}
