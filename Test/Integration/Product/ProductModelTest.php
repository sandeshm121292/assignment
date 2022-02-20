<?php

namespace Assignment\Test\Integration\Product;

use Assignment\Database\DbFactory;
use Assignment\Database\Exception\DbConnectionException;
use Assignment\Product\Exception\CannotFindProductException;
use Assignment\Product\Product;
use PHPUnit\Framework\TestCase;

class ProductModelTest extends TestCase
{

    /**
     * @return void
     * @throws DbConnectionException
     * @throws CannotFindProductException
     */
    public function testFindProduct(): void
    {
        $db = (new DbFactory())->createPDO();
        $product = new Product($db);
        $productResult = $product->findProduct('product-bread', 'FI');
        self::assertTrue(true);
    }
}
