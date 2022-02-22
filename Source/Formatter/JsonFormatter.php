<?php declare(strict_types=1);

namespace Assignment\Formatter;

class JsonFormatter extends AbstractFormatter implements FormatterInterface
{

    /**
     * @return string
     */
    public function getFormattedInvoice(): string
    {

        $consolidatedCalculationOutcome = $this->getConsolidatedCalculationOutcome();
        $outcomes = [];

        foreach ($consolidatedCalculationOutcome->getProductCalculationOutcomes() as $key => $outcome) {
            $outcomes[$key]['productId'] = $outcome->getProductId();
            $outcomes[$key]['quantity'] = $outcome->getQuantity();
            $outcomes[$key]['basePrice'] = $outcome->getBasePrice();
            $outcomes[$key]['taxPrice'] = $outcome->getTaxPrice();
            $outcomes[$key]['totalPrice'] = $outcome->getTotalPrice();
        }

        $response = [
            "data" => [
                "orderId" => $this->getOrderId(),
                "products" => $outcomes,
                "grandTotalTaxPrice" => $consolidatedCalculationOutcome->getGrandTotalTaxPrice(),
                "grandTotal" => $consolidatedCalculationOutcome->getGrandTotalPrice(),
            ]
        ];

        return json_encode($response, JSON_PRETTY_PRINT);
    }
}
