<?php declare(strict_types=1);

namespace Assignment\Formatter;

class JsonFormatter extends AbstractFormatter implements FormatterInterface
{

    /**
     * @return string
     */
    public function getFormattedInvoice(): string
    {

        $resource = $this->getResource();
        $outcomes = [];

        foreach ($resource->getOrders() as $key => $order) {
            $outcomes[$key]['productId'] = $order->getProductId();
            $outcomes[$key]['quantity'] = $order->getQuantity();
            $outcomes[$key]['basePrice'] = $order->getBasePrice();
            $outcomes[$key]['taxPrice'] = $order->getTaxPrice();
            $outcomes[$key]['totalPrice'] = $order->getTotalPrice();
        }

        $response = [
            "data" => [
                "orderId" => $resource->getOrders()[0]->getOrderId(),
                "products" => $outcomes,
                "grandTotalTaxPrice" => $resource->getGrandTotalTaxPrice(),
                "grandTotal" => $resource->getGrandTotal(),
            ]
        ];

        return json_encode($response, JSON_PRETTY_PRINT);
    }
}
