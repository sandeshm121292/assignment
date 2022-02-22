<?php

namespace Assignment\Test\Api\Order;

use Assignment\Database\Exception\DbConnectionException;
use Assignment\Test\Api\BaseApiTest;
use Assignment\Test\Helper\ApiTestHelper;
use Throwable;

final class OrderControllerTest extends BaseApiTest
{

    use ApiTestHelper;

    const PRODUCT_MILK = 'product-milk-3';

    const MILK_PRICE = 2.5;
    const MILK_COUNTRY_PL = 'PL';
    const MILK_TAX_PL = 2.5;

    const MILK_COUNTRY_FI = 'FI';
    const MILK_TAX_FI = 12.5;

    const PRODUCT_BREAD = 'product-bread-3';
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
     * @return void
     * @throws Throwable
     */
    public function testCreateJson(): void
    {
        $requestBody = [
            "products" => [
                [
                    "productId" => self::PRODUCT_MILK,
                    "quantity" => 10,
                ],
                [
                    "productId" => self::PRODUCT_BREAD,
                    "quantity" => 5,
                ],
            ],
            "country" => "FI",
            "invoiceFormat" => "json",
            "isSendEmail" => false,
            "email" => ""
        ];

        $body = json_encode($requestBody);

        $response = $this->dispatchPost('/api/order/create', $body);
        self::assertSame(200, $response->getStatusCode());
        $response = json_decode($response->getBody()->getContents(), true);
        self::assertIsArray($response);
        self::assertArrayHasKey('data', $response);
        $data = $response['data'];
        self::assertArrayHasKey('orderId', $data);
        self::assertArrayHasKey('products', $data);
        self::assertArrayHasKey('grandTotalTaxPrice', $data);
        self::assertArrayHasKey('grandTotal', $data);
    }

    /**
     * @return void
     * @throws Throwable
     */
    public function testCreateHtml(): void
    {
        $requestBody = [
            "products" => [
                [
                    "productId" => self::PRODUCT_MILK,
                    "quantity" => 10
                ]
            ],
            "country" => "FI",
            "invoiceFormat" => "html",
            "isSendEmail" => false,
            "email" => ""
        ];

        $body = json_encode($requestBody);

        $response = $this->dispatchPost('/api/order/create', $body);
        self::assertSame(200, $response->getStatusCode());
        $contents = $response->getBody()->getContents();
        self::assertIsString($contents);
        self::assertStringContainsString('<!DOCTYPE html>', $contents);
    }
}
