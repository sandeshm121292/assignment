<?php declare(strict_types=1);

namespace Assignment\Order;

use Assignment\Order\Resource\OrderResource;

class JsonFormatter extends AbstractFormatter implements FormatterInterface
{

    /**
     * @var bool
     */
    private $isSendEmail;

    /**
     * @param OrderResource $resource
     * @param bool $isSendEmail
     */
    public function __construct(OrderResource $resource, bool $isSendEmail)
    {
        parent::__construct($resource);
        $this->isSendEmail = $isSendEmail;
    }

    /**
     * @return string
     */
    public function getFormattedResponse(): string
    {

        if ($this->isSendEmail) {
            return json_encode([
                "data" => [
                    "status" => "OK",
                ]
            ]);
        }

        $resource = $this->getResource();
        $outcomes = [];

        foreach ($resource->getCalculatedOutComes() as $key => $outcome) {
            $outcomes[$key]['productId'] = $outcome->getProductId();
            $outcomes[$key]['quantity'] = $outcome->getQuantity();
            $outcomes[$key]['unitCost'] = $outcome->getUnitCost();
            $outcomes[$key]['totalCost'] = $outcome->getTotalCost();
            $outcomes[$key]['taxedCost'] = $outcome->getTaxedCost();
        }

        $response = [
            "data" => [
                "products" => $outcomes,
                "totalTaxedPrice" => $resource->getTotalTaxCost(),
                "totalPrice" => $resource->getTotalCost(),
            ]
        ];

        return json_encode($response, JSON_PRETTY_PRINT);
    }
}
