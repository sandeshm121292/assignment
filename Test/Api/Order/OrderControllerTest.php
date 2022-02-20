<?php

namespace Assignment\Test\Api\Order;

use Assignment\Order\Controller\OrderController;
use Assignment\Test\Helper\ApiTestHelper;
use PHPUnit\Framework\TestCase;
use Throwable;

class OrderControllerTest extends TestCase
{

    use ApiTestHelper;

    /**
     * @return void
     * @throws Throwable
     */
    public function testCreate(): void
    {
        $requestBody = [
            "products" => [
                [
                    "productId" => "product-milk",
                    "quantity" => 10
                ]
            ],
            "country" => "FI",
            "invoiceFormat" => "json",
            "isSendEmail" => false,
            "email" => ""

        ];

        $body = json_encode($requestBody);

        $response = $this->dispatchPost('/api/invoice/create', $body);
        var_dump(json_decode($response->getBody()->getContents()));
        self::assertSame(
            200,
            $response->getStatusCode()
        );
    }

}
