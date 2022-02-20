<?php declare(strict_types=1);

namespace Assignment\Order\Resource;

final class OrderResourceFactory
{

    /**
     * @param float $totalCost
     * @param float $totalTaxCost
     * @param array $calculatedOutcomes
     * @return OrderResource
     */
    public function createInvoiceResource(float $totalCost, float $totalTaxCost, array $calculatedOutcomes): OrderResource
    {
        return new OrderResource($totalCost, $totalTaxCost, $calculatedOutcomes);
    }

}
